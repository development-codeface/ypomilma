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
});
