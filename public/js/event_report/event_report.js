"use strict";
document.addEventListener("DOMContentLoaded", function () {
    var event_id = document.querySelector("#event_id").value;

    function fetchCoupons(status, page = 1) {
        $.ajax({
            url: "/admin/event_report_list/" + event_id,
            type: "GET",
            data: { status: status, page: page },
            success: function (response) {
                let tbody = $(".datatable-User tbody");
                tbody.empty();
                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        let statusHtml =
                            item.status === "redeem"
                                ? '<span class="badge bg-success">Redeemed</span>'
                                : '<span class="badge bg-danger">Not Redeemed</span>';

                        tbody.append(`
                        <tr>
                        <td>${item.participate?.name_of_president ?? ""}</td>
                        <td>${item.participate?.member_no ?? ""}</td>
                            <td>${item.user?.name ?? ""}</td>
                        <td>${
                            item.coupon_redeem_time
                                ? String(item.coupon_redeem_time).replace(
                                      /^(\d{4}-\d{2}-\d{2})[T\s](\d{2}:\d{2}:\d{2}).*$/,
                                      "$1 , $2"
                                  )
                                : ""
                        }</td>
                            <td>${item.coupon ?? ""}</td>
                            <td>${item.coupon_code ?? ""}</td>
                            <td>${statusHtml}</td>
                        </tr>
                    `);
                    });
                } else {
                    tbody.append(
                        `<tr><td colspan="7" class="text-center">No data found</td></tr>`
                    );
                }

                // Pagination buttons
                let pagination = $(".pagination");
                pagination.empty();

                for (let i = 1; i <= response.pagination.last_page; i++) {
                    let activeClass =
                        i === response.pagination.current_page ? "active" : "";
                    pagination.append(
                        `<li class="page-item ${activeClass}"><a class="page-link" href="#">${i}</a></li>`
                    );
                }

                // Handle page click
                $(".pagination a").click(function (e) {
                    e.preventDefault();
                    let page = parseInt($(this).text());
                    fetchCoupons(status, page);
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }

    // Radio button change
    document.querySelectorAll('input[name="couponFilter"]').forEach((radio) => {
        radio.addEventListener("change", function () {
            if (this.checked) {
                fetchCoupons($(this).val());
            }
        });
    });

    $(document).ready(function () {
        $('input[name="couponFilter"]').on("change", function () {
            if ($(this).is(":checked")) {
                fetchCoupons($(this).val());
            }
        });

        let checkedRadio = $('input[name="couponFilter"]:checked');
        if (checkedRadio.length > 0) {
            checkedRadio.trigger("change"); // This will fetch "All" on reload
        }

    
        $(".print-btn").on("click", function (e) {
            e.preventDefault();

            setTimeout(function () {
                let status = $('input[name="couponFilter"]:checked').val();  

                $.ajax({
                    url: "/admin/event_report_list/" + event_id,
                    type: "GET",
                    data: { status: status, page: 1, perpage: 9999 }, 
                    success: function (response) {
                        let tbody = $(".datatable-User tbody");
                        tbody.empty();
                        if (response.data.length > 0) {
                            $.each(response.data, function (index, item) {
                                let statusHtml =
                                    item.status === "redeem"
                                        ? '<span class="badge bg-success">Redeemed</span>'
                                        : '<span class="badge bg-danger">Not Redeemed</span>';

                                tbody.append(`
                                <tr>
                                    <td>${item.participate?.name_of_president ?? ""}</td>
                                    <td>${item.participate?.member_no ?? ""}</td>
                                    <td>${item.user?.name ?? ""}</td>
                                    <td>${
                                        item.coupon_redeem_time
                                            ? String(item.coupon_redeem_time).replace(
                                                  /^(\d{4}-\d{2}-\d{2})[T\s](\d{2}:\d{2}:\d{2}).*$/,
                                                  "$1 , $2"
                                              )
                                            : ""
                                    }</td>
                                    <td>${item.coupon ?? ""}</td>
                                    <td>${item.coupon_code ?? ""}</td>
                                    <td>${statusHtml}</td>
                                </tr>
                            `);
                            });
                        } else {
                            tbody.append(
                                `<tr><td colspan="7" class="text-center">No data found</td></tr>`
                            );
                        }
                  let allRows = document.querySelector(".table-responsive table").innerHTML;

                       
                        let printWindow = window.open("", "_blank", "width=800,height=600");
                        printWindow.document.write(`
                            <html>
                                <head>
                                    <title>Milma TRCMPU</title>
                                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                                </head>
                                <body>
                                    <div class="container">
                                        <table class="table">
                                            ${allRows}
                                        </table>
                                    </div>
                                </body>
                            </html>`);

                        printWindow.document.close();

                        printWindow.onload = function () {
                        printWindow.focus();       
                        printWindow.print();
                         setTimeout(function () {
                            printWindow.close();
                            location.reload();      
                        }, 500);
                    };
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    },
                });
            }, 500);
        });
    });
});
