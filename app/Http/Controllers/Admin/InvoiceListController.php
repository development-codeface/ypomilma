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
    public function statusChange(Request $request, $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        // ✅ Fetch the dairy's account
        $account = \App\Models\Account::where('dairy_id', $invoice->dairy_id)->first();

        if (!$account) {
            return response()->json(['error' => true, 'message' => 'No account found for this dairy.']);
        }

        $amount = $invoice->total_amount;

        if ($account->main_balance < $amount) {
            return response()->json(['error' => true, 'message' => 'Insufficient main balance to record this transaction.']);
        }

        $account->main_balance -= $amount;
        $account->save();

        $delivered_date = $request->date;

        if ($delivered_date == null) {
            $delivered_date = Carbon::now()->format('Y-m-d');
        }

        if ($invoice) {
            $invoice->status = 'delivered';
            $invoice->delivered_date = $delivered_date;
            $invoice->invoice_no =  $request->invoice_no;
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
        return response()->json(['success' => true, 'message' => 'Invoice status changed successfully.']);
    }
}
