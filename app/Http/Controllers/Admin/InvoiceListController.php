<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assets;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transactions;
use App\Models\Vendor;
use Carbon\Carbon;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use Illuminate\Support\Facades\DB;


class InvoiceListController extends Controller
{
    /**
     * function for view page invoice list.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $data['vendors'] = Vendor::all();
        $invoice_list = Invoice::with(['dairy', 'vendor']);

        if ($request->vendor_id) {
            $invoice_list->where('vendor_id', $request->vendor_id);
        }

        if ($request->status) {
            $invoice_list->where('status', $request->status);
        }

        if ($request->from_date && $request->to_date) {
            $invoice_list->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);
        } elseif ($request->from_date) {
            $invoice_list->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $invoice_list->whereDate('created_at', '<=', $request->to_date);
        }

        $user_id = auth()->user()->id;
        $invoice_list->whereHas('dairy', function ($query) use ($user_id) {
            $query->where('admin_userid', $user_id);
        });

        $data['invoice_list'] = $invoice_list->orderBy('id', 'DESC')->paginate(15);
        return view('admin.invoice_list.index', $data);
    }

    /**
     * function for status change.
     *
     * @return void
     */

    public function statusChangeorg(Request $request, $invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        // --- Validate ---
        $request->validate([
            'invoice_item_id' => 'required',
            'delivered_quantity' => 'required|numeric|min:1',
        ]);

        $item = InvoiceItem::findOrFail($request->invoice_item_id);

        $user = auth()->user();
      
        $delivery = Delivery::create([
            'delivery_no'   => $request->invoice_no,
            'invoice_id'    => $invoice_id,
            'created_by'    => $user->id,
            'delivery_date' => $request->date ?? now()->toDateString(),
            'notes'         => $request->description ?? null,
        ]);

        // create delivery item
        $deliveryItem = DeliveryItem::create([
            'delivery_id'       => $delivery->id,
            'invoice_item_id'   => $item->id,
            'product_id'        => $item->product_id,
            'delivered_quantity'=> (int)$request->delivered_quantity,
            'warranty'          => $request->warranty ?? null,
            'description'       => $request->description ?? null,
        ]);

        $previousDelivered = $item->delivered_quantity ?? 0;
        $newDelivered = $previousDelivered + $request->delivered_quantity;

        if ($newDelivered > $item->quantity) {
            return response()->json([
                'error' => true,
                'message' => 'Delivered quantity cannot exceed ordered quantity'
            ]);
        }

        // --- Update Delivered Qty ---
        $item->delivered_quantity = $newDelivered;
        $item->pending_quantity = $item->quantity - $newDelivered;
        $item->save();

        /* ------------------------------------------------------------
            UPDATE OR CREATE ASSET ENTRY
        ------------------------------------------------------------ */

        $existingAsset = Assets::where('invoice_refno', $invoice->id)
            ->where('product_id', $item->product_id)
            ->first();

        if ($existingAsset) {
            // Update quantity only
            $existingAsset->quantity += $request->delivered_quantity;
            $existingAsset->save();

        } else {
            // Create new asset entry
            Assets::create([
                'invoice_items_id' => $item->id,
                'dairy_id' => $invoice->dairy_id,
                'quantity' => $request->delivered_quantity,
                'product_id' => $item->product_id,
                'purchase_value' => $item->total,
                'purchase_date' => now(),
                'sold_price' => 0,
                'discount' => $item->discount,
                'invoice_refno' => $invoice->id,
                'status' => 'available',
            ]);
        }

        /* ------------------------------------------------------------
            CHECK IF FULLY DELIVERED
        ------------------------------------------------------------ */

        $pendingCount = InvoiceItem::where('invoice_id', $invoice->id)
            ->whereRaw('quantity > delivered_quantity')
            ->count();


            // // Deduct amount ONLY on full delivery
            // $account = \App\Models\Account::where('dairy_id', $invoice->dairy_id)->first();

            // if (!$account) {
            //     return response()->json(['error' => true, 'message' => 'No account found for this dairy']);
            // }

            // if ($account->main_balance < $invoice->total_amount) {
            //     return response()->json(['error' => true, 'message' => 'Insufficient main balance']);
            // }

            // $account->main_balance -= $invoice->total_amount;
            // $account->save();

            // Final invoice update
            if ($pendingCount == 0) 
             {
                $invoice->status = 'delivered';
                $invoice->delivered_date = $request->date ?? now()->toDateString();
             }
            $invoice->invoice_no = $request->invoice_no;
            $invoice->save();

            // // Create transaction
            // Transactions::create([
            //     'dairy_id' => $invoice->dairy_id,
            //     'type' => 'hold',
            //     'amount' => $invoice->total_amount,
            //     'description' => 'invoice item',
            //     'status' => 'completed',
            //     'transaction_date' => now(),
            // ]);
       
    }

public function statusChange(Request $request, $invoice_id)
{
    // Expect invoice_id to be the primary id or custom id stored in invoice->id (whatever you use)
    $invoice = Invoice::where('id', $invoice_id)->orWhere('invoice_no', $invoice_id)->first();

    if (!$invoice) {
        return response()->json(['error' => true, 'message' => 'Invoice not found'], 404);
    }

    $request->validate([
        'invoice_item_id'    => 'required',
        'delivered_quantity' => 'required|numeric|min:1',
        'invoice_no'         => 'nullable|string',
        'date'               => 'nullable|date',
        'warranty'           => 'nullable|string|max:255',
        'description'        => 'nullable|string|max:2000',
    ]);

    // wrap in transaction
    DB::beginTransaction();
    try {
        $item = InvoiceItem::findOrFail($request->invoice_item_id);

        $previousDelivered = $item->delivered_quantity ?? 0;
        $newDelivered = $previousDelivered + (int)$request->delivered_quantity;

        if ($newDelivered > $item->quantity) {
            return response()->json(['error' => true, 'message' => 'Delivered quantity cannot exceed ordered quantity'], 422);
        }

         $user = auth()->user();

        $delivery = Delivery::create([
            'delivery_no'   => $request->invoice_no,
            'invoice_id'    => $invoice_id,
            'created_by'    => $user->id,
            'delivery_date' => $request->date ?? now()->toDateString(),
            'notes'         => $request->description ?? null,
        ]);

        // create delivery item
        $deliveryItem = DeliveryItem::create([
            'delivery_id'       => $delivery->id,
            'invoice_item_id'   => $item->id,
            'product_id'        => $item->product_id,
            'delivered_quantity'=> (int)$request->delivered_quantity,
            'warranty'          => $request->warranty ?? null,
            'description'       => $request->description ?? null,
        ]);

        // update invoice item delivered & pending
        $item->delivered_quantity = $newDelivered;
        $item->pending_quantity = $item->quantity - $newDelivered;
        $item->save();

        // CASE 1 — If user entered serial details (each item separate asset row)
        if ($request->has('serial_items')) {

            foreach ($request->serial_items as $details) {
                Assets::create([
                    'invoice_items_id' => $item->id,
                    'dairy_id'         => $invoice->dairy_id,
                    'quantity'         => 1, // always 1
                    'product_id'       => $item->product_id,
                    'purchase_value'   => $item->total,
                    'purchase_date'    => now()->toDateString(),
                    'sold_price'       => 0,
                    'discount'         => $item->discount,
                    'invoice_refno'    => $invoice->id,
                    'status'           => 'available',

                    // NEW FIELDS
                    'brand'            => $details['brand'] ?? null,
                    'model'            => $details['model'] ?? null,
                    'serial_no'        => $details['serial_no'] ?? null,
                    'warranty'         => $details['warranty'] ?? null,
                ]);
            }
        }
        // CASE 2 — No serial details — simple qty addition
        else {
            $existingAsset = Assets::where('invoice_refno', $invoice_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingAsset) {
                $existingAsset->quantity += (int)$request->delivered_quantity;
                $existingAsset->save();
            } else {
                Assets::create([
                    'invoice_items_id' => $item->id,
                    'dairy_id'         => $invoice->dairy_id,
                    'quantity'         => (int)$request->delivered_quantity,
                    'product_id'       => $item->product_id,
                    'purchase_value'   => $item->total,
                    'purchase_date'    => now()->toDateString(),
                    'sold_price'       => 0,
                    'discount'         => $item->discount,
                    'invoice_refno'    => $invoice_id,
                    'status'           => 'available',
                ]);
            }
        }


        // Now check if entire invoice fully delivered

         $pendingCount = InvoiceItem::where('invoice_id', $invoice_id)
            ->whereRaw('quantity > COALESCE(delivered_quantity,0)')
            ->count();
        //echo $pendingCount;exit;
       
        if ($pendingCount == 0) {
            $invoice->status = 'delivered';
            $invoice->delivered_date = $request->date ?? now()->toDateString();
        } else {
            // if any pending, mark partially_delivered
            $invoice->status = 'partially_delivered';
        }

        // Update invoice_no if custom per delivery invoice_no is needed on invoice record:
        if ($request->filled('invoice_no')) {
            // Optionally store last delivered invoice no (if you want)
            $invoice->invoice_no = $request->invoice_no;
        }
        $invoice->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Delivery recorded successfully',
            'delivery_no' => $delivery->delivery_no,
        ]);
    } catch (\Exception $ex) {
        DB::rollBack();
        // log error if you want: \Log::error($ex);
        return response()->json(['error' => true, 'message' => 'Server error: ' . $ex->getMessage()], 500);
    }
}

    public function deliveryHistory($invoice_id)
    {
        $invoice = Invoice::where('id', $invoice_id)->orWhere('invoice_no', $invoice_id)->firstOrFail();

        $deliveries = Delivery::where('invoice_id', $invoice_id)
        ->with(['items.product'])   // <-- Load product name
        ->orderBy('created_at', 'desc')
        ->get();

        // return JSON for AJAX modal
        return response()->json(['deliveries' => $deliveries]);
    }
    public function getInvoiceItems($invoiceId)
    {
        $items = InvoiceItem::with('product')
            ->where('invoice_id', $invoiceId)
            ->get();

        return response()->json(['items' => $items]);
    }


}
