@extends('layouts.admin')

@section('content')
<div class="card w_120" >
    <div class="card-header d-flex justify-content-between align-items-center">
        <p>
            <i class="fi fi-br-eye mr_15_icc"></i> Invoice Details
        </p>
        <div>
            <button type="button" class="btn btn-success btn-sm me-2" onclick="printInvoice()">
                <i class="fi fi-br-print"></i> Print
            </button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                <i class="fi fi-br-angle-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body" id="invoiceArea">
        {{-- Invoice Header --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Dairy Information</h5>
                <p><strong>Dairy Name:</strong> {{ $invoice->dairy->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Vendor Information</h5>
                <p><strong>Vendor Name:</strong> {{ $invoice->vendor->name ?? '-' }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Invoice No:</strong> {{ $invoice->invoice_no }}
            </div>
            <div class="col-md-4">
                {{-- Status could go here --}}
            </div>
            <div class="col-md-4">
                <strong>Created Date:</strong> {{ $invoice->created_at->format('d M Y, h:i A') }}
            </div>
        </div>

        {{-- Invoice Items Table --}}
        <h5 class="mt-4">Invoice Items</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Pending Qty</th>
                        <th>Unit Price</th>
                        <th>GST %</th>
                        <th>Tax Type</th>
                        <th>Discount</th>
                        <th>Taxable Value</th>
                        <th>GST Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->productname }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ ($item->quantity ?? 0) - ($item->delivered_quantity ?? 0) }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->gst_percent }}%</td>
                            <td>
                                <span class="badge badge-{{ $item->tax_type == 'inclusive' ? 'info' : 'warning' }}">
                                    {{ ucfirst($item->tax_type) }}
                                </span>
                            </td>
                            <td>{{ number_format($item->discount, 2) }}</td>
                            <td>{{ number_format($item->taxable_value, 2) }}</td>
                            <td>{{ number_format($item->gst_amount, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        @php
            $subtotal = $invoice->items->sum('taxable_value');
            $gstTotal = $invoice->items->sum('gst_amount');
            $discount = $invoice->discount ?? 0;
            $grandTotal = $invoice->total_amount;
        @endphp

        <div class="row mt-4 justify-content-end">
            <div class="col-md-5">
                <table class="table table-borderless">
                    <tr>
                        <th>Subtotal:</th>
                        <td class="text-end">{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total GST:</th>
                        <td class="text-end">{{ number_format($gstTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Invoice Discount:</th>
                        <td class="text-end">{{ number_format($discount, 2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <th><h5>Grand Total:</h5></th>
                        <td class="text-end"><h5>{{ number_format($grandTotal, 2) }}</h5></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ✅ JavaScript for Printing --}}
<script>
function printInvoice() {
    const printContents = document.getElementById('invoiceArea').innerHTML;
    const originalContents = document.body.innerHTML;

    // Replace body content with the invoice only
    document.body.innerHTML = printContents;

    // Trigger print
    window.print();

    // Restore original page content after printing
    document.body.innerHTML = originalContents;

    // Reload scripts and styles
    window.location.reload();
}
</script>

{{-- Optional: print-specific styles --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoiceArea, #invoiceArea * {
        visibility: visible;
    }
    #invoiceArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn, .card-header .btn {
        display: none !important;
    }
}
</style>
@endsection
