"use strict";

document.addEventListener("DOMContentLoaded", function () {
    function fetchParticipants(query) {
        $.ajax({
            url: "/admin/participate/search",
            type: "GET",
            data: { q: query },
            success: function (data) {
                let participants = data[0];
                let resultsHtml = "";

                if (participants.length > 0) {
                    participants.forEach(function (item) {
                        resultsHtml += `
                        <button type="button"
                            class="list-group-item list-group-item-action participant-option"
                            data-id="${item.id}">
                            ${item.text}
                        </button>
                    `;
                    });
                } else {
                    resultsHtml = `<div class="list-group-item">Already Onboarded</div>`;
                }

                $("#searchResults").html(resultsHtml);
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }

    $(document).ready(function () {
        // Keyup search
        $("#searchInput").on("keyup", function () {
            let query = $(this).val();
            if (query.length >= 2) {
                fetchParticipants(query);
            } else {
                $("#searchResults").empty();
            }
        });

        // Handle click on result (same as select2:select logic)
        $(document).on("click", ".participant-option", function () {
            let selectedId = $(this).data("id");
            let event_id = $("#event_ids").val();

            $("#participateModal").modal("show");

            $.ajax({
                url: "/admin/get_participate_list/" + selectedId,
                type: "GET",
                success: function (response) {
                    $("#event_id").val(event_id);
                    var participate_data = response.data;
                    $("#participate_name").text(
                        participate_data.name_of_president
                    );
                    $("#register_no").text(
                        participate_data.registration_number
                    );
                    $("#pi_unit").text(participate_data.p_i_unit);
                    $("#participate_society").text(
                        participate_data.name_society
                    );
                    $("#district").text(participate_data.revenue_district);
                    $("#participate_mobile_number").text(
                        participate_data.mobile_number
                    );
                    $("#participate_id").val(participate_data.id);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
            });
        });
    });

    // $(document).on("click", "#btn-close", function () {
    //     $("#participateModal").modal("hide");

    //     $("#searchDropdownContainer").load(
    //         location.href + " #searchDropdownContainer > *",
    //         function () {
    //             $("#searchInput").on("keyup", function () {
    //                 let query = $(this).val();
    //                 if (query.length >= 2) {
    //                     fetchParticipants(query);
    //                 } else {
    //                     $("#searchResults").empty();
    //                 }
    //             });
    //         }
    //     );
    // });

    $(document).on("click", "#on_board", function (e) {
        $("#searchDropdownContainer").load(
            location.href + " #searchDropdownContainer > *",
            function () {
                $("#searchInput").on("keyup", function () {
                    let query = $(this).val();
                    if (query.length >= 2) {
                        fetchParticipants(query);
                    } else {
                        $("#searchResults").empty();
                    }
                });
            }
        );
    });
    $(document).on("submit", "#participateForm", function (e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append("event_id", $("#event_id").val());
        formData.append("participate_id", $("#participate_id").val());

        $.ajax({
            type: "post",
            url: "/admin/participate_store",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success == true) {

                    Swal.fire({
                        text: `${response.message}`,
                        icon: "success",
                        showCancelButton: false,
                        showDenyButton: true,
                        confirmButtonText: "Print",
                        denyButtonText: "OK",
                    }).then((result) => {
                        // Reload dropdown div
                        $("#searchDropdownContainer").load(
                            location.href + " #searchDropdownContainer > *",
                            function () {
                                $("#searchInput").on("keyup", function () {
                                    let query = $(this).val();
                                    if (query.length >= 2) {
                                        fetchParticipants(query);
                                    } else {
                                        $("#searchResults").empty();
                                    }
                                });
                            }
                        );

                        $("#participateModal").modal("hide");

                        if (result.isConfirmed) {
                            let printWindow = window.open(
                                `/admin/event/print/${response.data.id}`
                            );
                            printWindow.onload = function () {
                                printWindow.print();

                                // printWindow.onafterprint = function () {
                                //     printWindow.close();
                                // };
                            };
                        }
                    });
                }else if (response.success == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `${response.message}!`,
                    });
                } else if (response.status == 401) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `${response.message}!`,
                    });
                }
            },
            error: function (xhr) {
                console.error(xhr);
            },
        });
    });

    $(document).on("click", "#print_participate_event", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let printWindow = window.open(`/admin/event/print/${id}`);
        printWindow.onload = function () {
            printWindow.print();

            // printWindow.onafterprint = function () {
            //     printWindow.close();
            // };
        };
    });
});
