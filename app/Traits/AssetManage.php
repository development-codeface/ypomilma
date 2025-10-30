<?php

namespace App\Traits;

use App\Models\AggencyBill;
use App\Models\AggencySale;
use App\Models\Invoice;

class AssetManage
{

    public function AssetStore($data, $items)
    {
        $lastInvoice = AggencySale::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $lastInvoice ? (int) substr($lastInvoice->id, 3) + 1 : 1;
        $invoiceId = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);

        $agency_sale = AggencySale::create([
            'invoice_id' => $invoiceId,
            'name' => $data['name'],
            'address' => $data['address'],
            'contact_no' => $data['contact_no'],
            'total_amount' => array_sum(array_column($items, 'total')),
        ]);

        foreach ($items as $item) {
            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];
            $discount = $item['discount'] ?? 0;
            $gstPercent = $item['gst_percent'];
            $taxType = $item['tax_type'];

              $baseValue = ($unitPrice * $quantity) - $discount;
                $gstAmount = 0;
                $itemTotal = 0;
                $taxableValue = 0;

                if ($taxType === 'inclusive') {
                    $taxableValue = $baseValue / (1 + $gstPercent / 100);
                    $gstAmount = $baseValue - $taxableValue;
                    $itemTotal = $baseValue;
                } else {
                    $taxableValue = $baseValue;
                    $gstAmount = $baseValue * ($gstPercent / 100);
                    $itemTotal = $taxableValue + $gstAmount;
                }

                AggencyBill::create([
                    'aggency_sale_id' => $agency_sale->id,
                    'asset_id' => $item['asset_id'],
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'gst_percent' => $gstPercent,
                    'tax_type' => $taxType,
                    'gst_amount' => $gstAmount,
                    'discount' => $discount,
                    'total' => $itemTotal,
                ]);
        }
    }
}
