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
    public function statusChange($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if ($invoice) {
            $invoice->status = 'delivered';
            $invoice->save();
        }

        Transactions::create([
            'dairy_id' => $invoice->dairy_id,
            'fund_allocation_id' => null,
            'type' => 'hold',
            'amount' => $invoice->total_amount,
            'description' => 'invoice item',
            'status' => 'completed',
            'transaction_date' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $invoice_items = InvoiceItem::with('invoice')->where('invoice_id', $invoice_id)->get();

        foreach ($invoice_items as $item) {
            Assets::create([
                'invoice_items_id' => $item->id,
                'dairy_id' => $item->invoice->dairy_id,
                'quantity' => $item->quantity,
                'product_id' => $item->product_id,
                'purchase_value' => $item->total,
                'purchase_date' => Carbon::now()->format('Y-m-d'),
                'sold_price' => 0,
                'discount' => $item->discount,
                'invoice_refno' => $item->invoice_id,
                'status' => 'sold',
            ]);
        }

        return redirect()->back()->with('success', 'Invoice status changed successfully.');
    }
}
