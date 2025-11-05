<?php

namespace App\Traits;

use App\Models\AggencyBill;
use App\Models\AggencySale;
use App\Models\Invoice;
use App\Models\Assets;
use App\Models\Account;

class AssetManage
{

    // public function AssetStore($data, $items, $dairy_id, $asset)
    // {
    //     // $lastInvoice = AggencySale::lockForUpdate()->orderBy('id', 'desc')->first();
    //     // $num = $lastInvoice ? (int) substr($lastInvoice->id, 3) + 1 : 1;
    //     // $invoiceId = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);
    //     // $total_amount = array_sum(array_column($items, 'total'));

    //     // $agency_sale = AggencySale::create([
    //     //     'invoice_id' => $invoiceId,
    //     //     'name' => $data['name'],
    //     //     'address' => $data['address'],
    //     //     'contact_no' => $data['contact_no'],
    //     //     'total_amount' => $total_amount,
    //     // ]);

    //     // $account = Account::where('dairy_id', $dairy_id)->first();
    //     // $account->main_balance = $account->main_balance + $total_amount;
    //     // $account->save();

    //     // foreach ($items as $item) {
    //     //     $quantity = $item['quantity'];
    //     //     $unitPrice = $item['unit_price'];
    //     //     $discount = $item['discount'] ?? 0;
    //     //     $gstPercent = $item['gst_percent'];
    //     //     $taxType = $item['tax_type'];

    //     //     if ($quantity > $asset->quantity) {
    //     //         return response()->json(['error' => true, 'message' => "Requested ({$asset->product->productname}) quantity ({$item['quantity']}) exceeds available stock ({$asset->quantity})."]);
    //     //     }

    //     //     $baseValue = ($unitPrice * $quantity) - $discount;
    //     //     $gstAmount = 0;
    //     //     $itemTotal = 0;
    //     //     $taxableValue = 0;

    //     //     if ($taxType === 'inclusive') {
    //     //         $taxableValue = $baseValue / (1 + $gstPercent / 100);
    //     //         $gstAmount = $baseValue - $taxableValue;
    //     //         $itemTotal = $baseValue;
    //     //     } else {
    //     //         $taxableValue = $baseValue;
    //     //         $gstAmount = $baseValue * ($gstPercent / 100);
    //     //         $itemTotal = $taxableValue + $gstAmount;
    //     //     }

    //     //     $quantity_asset = Assets::where('id', $item['asset_id'])->first();
    //     //     $quantity_asset->quantity = $quantity_asset->quantity - $quantity;
    //     //     $quantity_asset->save();

    //     //     AggencyBill::create([
    //     //         'aggency_sale_id' => $agency_sale->id,
    //     //         'asset_id' => $item['asset_id'],
    //     //         'quantity' => $quantity,
    //     //         'price' => $unitPrice,
    //     //         'gst_percent' => $gstPercent,
    //     //         'tax_type' => $taxType,
    //     //         'gst_amount' => $gstAmount,
    //     //         'discount' => $discount,
    //     //         'total' => $itemTotal,
    //     //     ]);
    //     // }
    // }
}
