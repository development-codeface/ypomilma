@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Invoices List</p>
            {{-- <a href="{{ route('admin.invoices.create') }}" class="btn btn-success">
            <i class="fi fi-br-plus-small mr_5"></i> Add Invoice
        </a> --}}
        </div>

        <div class="card-body">
            {{-- 🔍 Filters --}}
            <form method="GET" action="{{ route('admin.invoice-list.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-control">
                            <option value="">All Vendors</option>
                            @foreach ($vendors as $vendor)
                                <option value=" {{ $vendor->id }}"
                                    {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-3">
                        <label for="dairy_id">Dairy</label>
                        <select name="dairy_id" id="dairy_id" class="form-control">
                            <option value="">All Dairies</option>
                            @foreach ($dairies as $dairy)
                            <option value="">
                                {{ $dairy->name }}
                            </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-2">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
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
                        <button type="submit" class="btn btn-primary me-2 filter-btns">
                            <i class="fi fi-br-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.invoice-list.index') }}" class="btn btn-secondary filter-btns">
                            <i class="fi fi-br-rotate-left"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- 🧾 Invoice Table --}}
            <div id="invoiceTableContainer">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('cruds.invoice.fields.id') }}</th>
                                <th>{{ trans('cruds.invoice.fields.diary') }}</th>
                                <th>{{ trans('cruds.invoice.fields.vendor') }}</th>
                                <th>{{ trans('cruds.invoice.fields.total_amount') }}</th>
                                <th>{{ trans('cruds.invoice.fields.status') }}</th>
                                <th>{{ trans('cruds.invoice.fields.created_at') }}</th>
                                <th>{{ trans('cruds.invoice.fields.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice_list as $invoice)
                                <tr>
                                    <td>{{ $invoice->id ?? 'N/A' }}</td>
                                    <td>{{ $invoice->dairy->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->vendor->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->total_amount ?? 'N/A' }}</td>
                                    <td>
                                        @if ($invoice->status == 'pending')
                                            <span class="badge bg-success">pending</span>
                                        @elseif($invoice->status == 'delivered')
                                            <span class="badge bg-info">Delivered</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($invoice->status !== 'cancelled')
                                            <button type="button" class="btn btn-xs btn-warning" id="invoice_status_btn"
                                                data-bs-toggle="modal" data-id="{{ $invoice->id }}"
                                                data-bs-target="#invoice_modal"
                                                @if ($invoice->status == 'delivered') disabled @endif>
                                                @if ($invoice->status == 'pending')
                                                    <i class="fi fi-br-ban"></i> Pending
                                                @elseif($invoice->status == 'delivered')
                                                    <i class="fi fi-br-check"></i> Delivered
                                                @else
                                                    <i class="fi fi-br-close"></i> Cancelled
                                                @endif
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No invoices found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $invoice_list->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
    <div class="modal fade" id="invoice_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="statusChangeForm">
                    @csrf
                    <input type="hidden" name="invoice_id" id="invoice_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Status Change</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="" for="date">Delivered Date</label>
                            <input class="form-control" type="date" name="date" id="date"
                                value="{{ old('date', '') }}">

                        </div>
                        <div class="form-group">
                            <label class="" for="invoice_no">Invoice No</label>
                            <input class="form-control" type="text" name="invoice_no" id="invoice_no"
                                value="{{ old('invoice_no', '') }}">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="status_change_btn"
                            data-bs-dismiss="modal">Submit</button>

                        {{-- <button type="button" class="btn btn-danger" id="modal_hide" data-bs-dismiss="modal">Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/asset_management/invoice.js') }}"></script>
@endsection
