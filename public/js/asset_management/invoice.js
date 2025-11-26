"use strict";
document.addEventListener("DOMContentLoaded", function () {
    $(document).on("change", ".product-select", function () {
        const $row = $(this).closest("tr"); // find the current row
        const assetId = $(this).val();

        if (assetId) {
            $.ajax({
                url: "/admin/get-asset-details/" + assetId,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    if (data?.data?.invoice_item) {
                        $row.find(".price").val(
                            data.data.invoice_item.unit_price
                        );
                        $row.find(".gst").val(
                            data.data.invoice_item.gst_percent
                        );
                        $row.find(".tax-type").val(
                            data.data.invoice_item.tax_type
                        );

                        calculateRowTotal($row[0]);
                    }
                },
                error: function () {
                    console.error("Failed to fetch asset details.");
                },
            });
        }
    });

    $("#saveInvoiceBtn").on("click", function (e) {
        e.preventDefault();
        let formData = new FormData($("form")[0]);

        $.ajax({
            url: "/admin/asset-management/invoice/store",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    $("form")[0].reset();
                    $(".invalid-feedback").remove(); // remove old errors
                    $(".is-invalid").removeClass("is-invalid");
                    Swal.fire({
                        text: `${response.message}`,
                        icon: "success",
                        showCancelButton: false,
                        showDenyButton: true,
                        confirmButtonText: "ok",
                        denyButtonText: "Cancel",
                    }).then((result) => {
                        location.href = "/admin/aggency-sale";
                    });
                } else if (response.error) {
                    // $("form")[0].reset();
                    Swal.fire({
                        text: `${response.message}`,
                        icon: "error",
                    });

                    if (
                        response.saved_indices &&
                        response.saved_indices.length > 0
                    ) {
                        response.saved_indices.forEach((i) => {
                            $("#itemsTable tbody tr").eq(i).remove();
                        });
                    }

                    // // 🔥 Keep only failed row visible
                    // if (response.failed_index !== undefined) {
                    //     const failedRow = $("#itemsTable tbody tr").eq(
                    //         response.failed_index
                    //     );
                    //     failedRow.addClass("table-danger"); // highlight error row
                    // }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $(".alert-danger").remove(); // remove old errors
                    $.each(errors, function (key, value) {
                        let formattedKey =
                            key
                                .replace(/\.(\d+)\./g, "[$1][")
                                .replace(/\./g, "][") + "]";
                        let input = $(`[name="${formattedKey}"]`);
                        console.log("input", input);
                        if (input.length > 0) {
                            input.addClass("is-invalid");
                            input.after(
                                `<div class="invalid-feedback d-block">${value[0]}</div>`
                            );
                        } else {
                            // For general/global errors (like 'items' or 'quantity')
                            $("#invoiceForm").prepend(
                                `<div class="alert alert-danger mb-3">${value[0]}</div>`
                            );
                        }
                    });
                }
            },
        });
    });

    $("#status_change_btn").on("click", function (e) {
        e.preventDefault();
        let formData = new FormData($("#statusChangeForm")[0]);
        console.log("formData", formData);
        let invoice_id = $("#invoice_id").val();

        $.ajax({
            url: "/admin/invoice/status/change/" + invoice_id,
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    Swal.fire('Saved', res.message, 'success').then(() => {
                        $("#invoice_modal").modal("hide");
                        // reload invoice table area only
                        $("#invoiceTableContainer").load(location.href + " #invoiceTableContainer > *");
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed', 'error');
                }
            },
            error: function (xhr) {
               console.log(xhr);
            },
        });
    });

    $(document).on("click", "#invoice_status_btn", function () {
        let invoiceId = $(this).data("id");
        $("#invoice_id").val(invoiceId);
      //  alert(invoiceId);

        $.ajax({
            url: "/admin/get-invoice-items/" + invoiceId,
            type: "GET",
            success: function (res) {
                let options = "";

                res.items.forEach(item => {
                    let delivered = item.delivered_quantity ?? 0;
                    let pending = item.quantity - delivered;

                    options += `
                        <option value="${item.id}">
                            ${item.product.productname} - Qty: ${item.quantity}, Pending: ${pending}
                        </option>`;
                });

                $("#item_selector").html(options);
            }
        });

        $("#invoice_modal").modal("show"); // ✅ Correct modal ID
    });

    // Delivery history: open modal and load history
    $(document).on("click", ".delivery-history-btn", function () {
        let invoiceId = $(this).data('id');
        $("#deliveryHistoryBody").html('<div class="text-center">Loading…</div>');
        $("#deliveryHistoryModal").modal('show');

        $.ajax({
            url: "/admin/invoice/" + invoiceId + "/deliveries",
            type: "GET",
            success: function (res) {
                if (!res.deliveries || res.deliveries.length === 0) {
                    $("#deliveryHistoryBody").html('<div class="text-center">No deliveries found</div>');
                    return;
                }

                let html = '<div class="list-group">';
                res.deliveries.forEach(d => {
                    html += `<div class="list-group-item mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>${d.delivery_no}</strong>
                            <small>${d.delivery_date ?? d.created_at}</small>
                        </div>
                        <table class="table table-sm mt-2">
                            <thead><tr><th>Product</th><th>Qty</th><th>Warranty</th><th>Description</th></tr></thead>
                            <tbody>`;
                    (d.items || []).forEach(it => {
                        // item product name might not be loaded; if not present show product_id
                        html += `<tr>
                            <td>${(it.product && it.product.productname) ? it.product.productname : it.product_id}</td>
                            <td>${it.delivered_quantity}</td>
                            <td>${it.warranty ?? '-'}</td>
                            <td>${it.description ?? '-'}</td>
                        </tr>`;
                    });
                    html += `</tbody></table></div>`;
                });
                html += '</div>';
                $("#deliveryHistoryBody").html(html);
            },
            error: function () {
                $("#deliveryHistoryBody").html('<div class="text-danger">Failed to load history</div>');
            }
        });
    });

    // Show/hide serial details section
$("#enable_serial_details").on("change", function () {
    if ($(this).is(":checked")) {
        $("#serialDetailsContainer").show();
    } else {
        $("#serialDetailsContainer").hide();
        $("#serialRepeater").html("");
    }
});

// Add repeater row
$(document).on("click", "#addSerialBtn", function () {
    let index = $("#serialRepeater .serialRow").length;

    $("#serialRepeater").append(`
        <div class="serialRow border p-2 mb-2">
            <div class="row">
                <div class="col-md-3">
                    <label>Brand</label>
                    <input type="text" class="form-control" name="serial_items[${index}][brand]">
                </div>
                <div class="col-md-3">
                    <label>Model</label>
                    <input type="text" class="form-control" name="serial_items[${index}][model]">
                </div>
                <div class="col-md-3">
                    <label>Serial No</label>
                    <input type="text" class="form-control" name="serial_items[${index}][serial_no]">
                </div>
                <div class="col-md-2">
                    <label>Warranty</label>
                    <input type="text" class="form-control" name="serial_items[${index}][warranty]">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeSerialRow">&times;</button>
                </div>
            </div>
        </div>
    `);
});

$(document).on("click", ".removeSerialRow", function () {
    $(this).closest(".serialRow").remove();
});


});
