@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-list mr_15_icc"></i> Invoices List</p>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-success">
            <i class="fi fi-br-plus-small mr_5"></i> Add Invoice
        </a>
    </div>

    <div class="card-body">

        {{-- 🔍 Filters --}}
        <form method="GET" action="{{ route('admin.invoices.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="dairy_id">Dairy</label>
                    <select name="dairy_id" id="dairy_id" class="form-control">
                        <option value="">All Dairies</option>
                        @foreach($dairies as $dairy)
                            <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                                {{ $dairy->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="from_date">From</label>
                    <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-2">
                    <label for="to_date">To</label>
                    <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-12 mt-3 text-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fi fi-br-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                        <i class="fi fi-br-rotate-left"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- 🧾 Invoice Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Dairy</th>
                        <th>Vendor</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Delivered date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->dairy->name ?? 'N/A' }}</td>
                            <td>{{ $invoice->vendor->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                @if($invoice->status == 'pending')
                                    <span class="badge bg-success">Pending</span>
                                @elseif($invoice->status == 'delivered')
                                    <span class="badge bg-info">Delivered</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                           <td>
                                {{ $invoice->delivered_date 
                                    ? \Carbon\Carbon::parse($invoice->delivered_date)->format('d-m-Y') 
                                    : 'N/A' }}
                            </td>

                             <td>
                                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-xs btn-primary">
                                    <i class="fi fi-br-eye"></i>
                                </a>

                                @if($invoice->status === 'pending')
                                    <form action="{{ route('admin.invoices.cancel', $invoice->id) }}" 
                                          method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-warning"
                                                onclick="return confirm('Cancel this invoice?')">
                                            <i class="fi fi-br-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No invoices found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $invoices->links() }}
        </div>

    </div>
</div>

@endsection
