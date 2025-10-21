"use strict";

document.addEventListener("DOMContentLoaded", function () {
    var event_id = document.querySelector("#event_id").value;

    function participate(status, district, p_i_unit, page = 1) {
        $.ajax({
            url: "/admin/event/participate/report/list/" + event_id,
            type: "GET",
            data: {
                status: status,
                district: district,
                p_i_unit: p_i_unit,
                page: page,
            },
            success: function (response) {
                let tbody = $(".datatable-User tbody");
                tbody.empty();
                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        let statusHtml =
                            item.status === "attended"
                                ? '<span class="badge bg-success">Attended</span>'
                                : '<span class="badge bg-danger">Not Attended</span>';

                        tbody.append(`
                        <tr>
                        <td>${item.participate_name ?? ""}</td>
                        <td>${item.district ?? ""}</td>
                        <td>${item.p_i_unit ?? ""}</td>
                        <td>${item.user_name ?? ""}</td>
                        <td>${
                            item.created_at
                                ? String(item.created_at).replace(
                                      /^(\d{4}-\d{2}-\d{2})[T\s](\d{2}:\d{2}:\d{2}).*$/,
                                      "$1 , $2"
                                  )
                                : ""
                        }</td>
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

                let currentPage = response.pagination.current_page;
                let lastPage = response.pagination.last_page;

                // Previous button
                pagination.append(`
                    <li class="page-item ${
                        currentPage === 1 ? "disabled" : ""
                    }">
                        <a class="page-link prev-page" href="#">Previous</a>
                    </li>
                `);

                // Page numbers (limit to range)
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(lastPage, currentPage + 2);

                // Always show first page
                if (start > 1) {
                    pagination.append(
                        `<li class="page-item"><a class="page-link page-num" href="#">1</a></li>`
                    );
                    if (start > 2) {
                        pagination.append(
                            `<li class="page-item disabled"><span class="page-link">...</span></li>`
                        );
                    }
                }

                // Main range
                for (let i = start; i <= end; i++) {
                    let activeClass = i === currentPage ? "active" : "";
                    pagination.append(
                        `<li class="page-item ${activeClass}"><a class="page-link page-num" href="#">${i}</a></li>`
                    );
                }

                // Always show last page
                if (end < lastPage) {
                    if (end < lastPage - 1) {
                        pagination.append(
                            `<li class="page-item disabled"><span class="page-link">...</span></li>`
                        );
                    }
                    pagination.append(
                        `<li class="page-item"><a class="page-link page-num" href="#">${lastPage}</a></li>`
                    );
                }

                // Next button
                pagination.append(`
                    <li class="page-item ${
                        currentPage === lastPage ? "disabled" : ""
                    }">
                        <a class="page-link next-page" href="#">Next</a>
                    </li>
                `);

                // Handle click events
                $(".pagination a").click(function (e) {
                    e.preventDefault();
                    let newPage = currentPage;

                    if ($(this).hasClass("prev-page")) {
                        newPage = currentPage - 1;
                    } else if ($(this).hasClass("next-page")) {
                        newPage = currentPage + 1;
                    } else if ($(this).hasClass("page-num")) {
                        newPage = parseInt($(this).text());
                    }

                    if (!$(this).parent().hasClass("disabled")) {
                        participate(status, district, p_i_unit, newPage);
                    }
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }

    // document
    //     .querySelectorAll("#participateFilter")
    //     .forEach((select) => {
    //         select.addEventListener("change", function () {

    //             let value = this.value;
    //             participate(value);
    //         });

    //     });

    $(document).ready(function () {
        $("#participateFilter").on("change", function () {
            let district = $("#district").val();
            let p_i_unit = $("#p_i_unit").val();
            let value = $(this).val();
            participate(value, district, p_i_unit);
        });

        $("#district").on("change", function () {
            let district = $(this).val();
            let p_i_unit = $("#p_i_unit").val();
            let value = $("#participateFilter").val();
            participate(value, district, p_i_unit);
        });

        $("#p_i_unit").on("change", function () {
            let district = $("#district").val();
            let p_i_unit = $(this).val();
            let value = $("#participateFilter").val();
            participate(value, district, p_i_unit);
        });

        $("#participateFilter").trigger("change");
    });

      $(".print-btn").on("click", function (e) {
    e.preventDefault();

    setTimeout(function () {
        let status = $("#participateFilter").val();   // Get current status filter
        let district = $('#district').val();          // Get current district filter
        let p_i_unit = $('#p_i_unit').val();          // Get current p_i_unit filter

        // Fetch all data (ignoring pagination) for printing
        $.ajax({
            url: "/admin/event/participate/report/list/" + event_id,
            type: "GET",
            data: { status: status, district: district, p_i_unit: p_i_unit, page: 1, perpage: 9999 },  // Request all records
            success: function (response) {
                let tbody = $(".datatable-User tbody");
                tbody.empty();
                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        let statusHtml =
                            item.status === "attended"
                                ? '<span class="badge bg-success">Attended</span>'
                                : '<span class="badge bg-danger">Not Attended</span>';

                        tbody.append(`
                        <tr>
                            <td>${item.participate_name ?? ""}</td>
                            <td>${item.district ?? ""}</td>
                            <td>${item.p_i_unit ?? ""}</td>
                            <td>${item.user_name ?? ""}</td>
                            <td>${
                                item.created_at
                                    ? String(item.created_at).replace(
                                          /^(\d{4}-\d{2}-\d{2})[T\s](\d{2}:\d{2}:\d{2}).*$/,
                                          "$1 , $2"
                                      )
                                    : ""
                            }</td>
                            <td>${statusHtml}</td>
                        </tr>
                    `);
                    });
                } else {
                    tbody.append(
                        `<tr><td colspan="7" class="text-center">No data found</td></tr>`
                    );
                }

                // Once we have all the rows, prepare the content for printing
                let allRows = document.querySelector(".table-responsive table").innerHTML;

                // Create a print window
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
                }, 1000);
            };
                        },
            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }, 500);
});

    // $(".print-btn").on("click", function (e) {
    //     e.preventDefault();

    //     setTimeout(function () {
    //         let tableContent =
    //             document.querySelector(".table-responsive").innerHTML;
    //         let printWindow = window.open("", "_blank", "width=800,height=600");
    //         printWindow.document.write(`<html>
    //             <head>
    //                 <title>Milma TRCMPU</title>
    //                 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    //             </head>
    //             <body>
    //                 ${tableContent}
    //             </body>
    //         </html>`);

    //         printWindow.document.close();

    //         printWindow.onload = function () {
    //             printWindow.print();
    //             printWindow.close();
    //         };
    //     }, 500);
    // });

    // $(".print-btn").on("click", function (e) {
    //     e.preventDefault();

    //     setTimeout(function () {
    //         // Clone the full table content (all rows, not just visible ones)
    //         let table = document.querySelector(".datatable-User");
    //         let cloneTable = table.cloneNode(true);

    //         // Remove pagination (if exists inside .table-responsive)
    //         let pagination = document.querySelector(".pagination");
    //         if (pagination) {
    //             pagination.remove();
    //         }

    //         // // Remove search / length / info controls (if DataTables generated them)
    //         // let unwanted = cloneTable.parentElement.querySelectorAll('.dataTables_length, .dataTables_filter, .dataTables_info');
    //         // unwanted.forEach(el => el.remove());

    //         // Open print window
    //         let printWindow = window.open("", "_blank", "width=800,height=600");
    //         printWindow.document.write(`
    //         <html>
    //             <head>
    //                 <title>Milma TRCMPU</title>
    //                 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    //                 <style>
    //                     table { width: 100%; border-collapse: collapse; font-size: 12px; }
    //                     th, td { border: 1px solid #000; padding: 4px; }
    //                     .pagination, .dataTables_paginate, .dataTables_filter, .dataTables_length, .dataTables_info {
    //                         display: none !important;
    //                     }
    //                 </style>
    //             </head>
    //             <body>
    //                 ${cloneTable.outerHTML}
    //             </body>
    //         </html>
    //     `);

    //         printWindow.document.close();

    //         printWindow.onload = function () {
    //             printWindow.print();
    //             printWindow.close();
    //         };
    //     }, 500);
    // });
});
