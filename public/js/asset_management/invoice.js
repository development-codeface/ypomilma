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
});
