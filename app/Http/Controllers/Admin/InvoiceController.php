<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Dairy;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['dairy', 'vendor']);

        // --- Filters ---
        if ($request->dairy_id) {
            $query->where('dairy_id', $request->dairy_id);
        }
        if ($request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(10);
        $dairies = Dairy::all();
        $vendors = Vendor::all();

        return view('admin.invoices.index', compact('invoices', 'dairies', 'vendors'));
    }

    public function create()
    {
        $dairies = Dairy::select('id', 'name')->get(); 
        $vendors = Vendor::select('id', 'name')->get();
        $products = Product::all();
        return view('admin.invoices.create', compact('dairies', 'vendors','products'));
    }

    public function store(Request $request)
{
    $request->validate([
        'dairy_id' => 'required|exists:dairies,id',
        'vendor_id' => 'nullable|exists:vendors,id',
        'discount' => 'nullable|numeric',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.tax_type' => 'required|in:inclusive,exclusive',
    ]);

        DB::transaction(function () use ($request) {
        $lastInvoice = Invoice::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $lastInvoice ? (int) substr($lastInvoice->id, 3) + 1 : 1;
        $invoiceId = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'id' => $invoiceId,
            'dairy_id' => $request->dairy_id,
            'vendor_id' => $request->vendor_id,
            'discount' => $request->discount ?? 0,
            'total_amount' => 0,
            'status' => 'approved',
        ]);

        $grandTotal = 0;

        foreach ($request->items as $item) {
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

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'gst_percent' => $gstPercent,
                'tax_type' => $taxType,
                'gst_amount' => $gstAmount,
                'discount' => $discount,
                'taxable_value' => $taxableValue,
                'total' => $itemTotal,
            ]);

            $grandTotal += $itemTotal;
        }

        $invoice->update(['total_amount' => $grandTotal - $invoice->discount]);
    });

    return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
}

    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'dairy', 'vendor');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $dairies = Dairy::pluck('name', 'id');
        $vendors = Vendor::pluck('name', 'id');
        return view('admin.invoices.edit', compact('invoice', 'dairies', 'vendors'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->only('discount', 'status'));
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted');
    }

    public function cancel($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status !== 'cancelled') {
            $invoice->status = 'cancelled';
            $invoice->save();
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice has been cancelled successfully.');
    }

}
