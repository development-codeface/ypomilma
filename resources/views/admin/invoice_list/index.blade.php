@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Purchase Order List</p>
            @php
            $user = auth()->user();
            $roleName = strtolower($user->role_name);
            @endphp
            @if ($roleName === 'superadmin')
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-success">
                    <i class="fi fi-br-plus-small mr_5"></i> Add Invoice
                </a>
            @endif    
        </div>

        <div class="card-body">
            {{-- 🔍 Filters --}}
            <form method="GET" action="{{ route('admin.invoices.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">
                 @if ($roleName === 'superadmin')
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
                 @endif 
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
<div id="invoiceTableContainer">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Invoice No</th>
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
                        <td>{{ $invoice->invoice_no ?? 'N/A' }}</td>
                        <td>{{ $invoice->dairy->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->vendor->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->total_amount ?? 'N/A' }}</td>
                        <td>
                            @if ($invoice->status == 'pending')
                                <span class="badge bg-success">pending</span>
                            @elseif($invoice->status == 'delivered')
                                <span class="badge bg-info">Delivered</span>
                            @elseif($invoice->status == 'partially_delivered')
                                <span class="badge bg-info">Partially Delivered</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                        <td>
                            <!-- View Button -->
                            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-xs btn-primary">
                                <i class="fi fi-br-eye"></i>
                            </a>

                                <!-- Delivery History -->
                                   @if (in_array($invoice->status, ['delivered', 'partially_delivered']))
                                    <button type="button" class="btn btn-xs btn-primary delivery-history-btn"
                                        data-id="{{ $invoice->id }}" style="border: none !important; box-shadow: none !important;">
                                        <i class="fi fi-br-document"></i>
                                    </button>
                                    @endif

                           @if ($roleName === 'superadmin')  <!-- Super Admin logic -->
                                @if (!in_array($invoice->status, ['delivered', 'partially_delivered','cancelled'])) <!-- Cancel button only if status is not "delivered" -->
                                    <form action="{{ route('admin.invoices.cancel', $invoice->id) }}" 
                                          method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-warning"
                                                onclick="return confirm('Cancel this invoice?')">
                                            <i class="fi fi-br-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                @if ($invoice->dairy_id == 1)  <!-- Super Admin only updates invoices for dairy_id = 1 -->
                                    <button type="button" class="btn btn-xs btn-warning" id="invoice_status_btn"
                                        data-bs-toggle="modal" data-id="{{ $invoice->id }}"
                                        data-bs-target="#invoice_modal"
                                        @if ($invoice->status == 'delivered') disabled @endif>
                                         Update
                                      </button>
                                @endif
                            @else
                                <!-- Non-Super Admin logic: Already restricted to their own dairy -->
                                @if ($invoice->status !== 'cancelled')
                                    <button type="button" class="btn btn-xs btn-warning" id="invoice_status_btn"
                                        data-bs-toggle="modal" data-id="{{ $invoice->id }}"
                                        data-bs-target="#invoice_modal"
                                        @if ($invoice->status == 'delivered') disabled @endif>
                                        Update
                                     </button>
                                @endif
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
                    <h5 class="modal-title">Update Delivery Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label>Delivered Date</label>
                        <input class="form-control" type="date" name="date" id="date">
                    </div>

                    <div class="form-group mb-3">
                        <label>Invoice No</label>
                        <input class="form-control" type="text" name="invoice_no" id="invoice_no">
                    </div>

                    <div class="form-group mb-3">
                        <label>Select Item</label>
                        <select class="form-control" name="invoice_item_id" id="item_selector"></select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Delivered Quantity</label>
                        <input class="form-control" type="number" name="delivered_quantity" id="delivered_quantity">
                    </div>

                    <div class="form-group mb-3">
                    <label>
                        <input type="checkbox" id="enable_serial_details"> This product has Serial/Warranty details
                    </label>
                </div>

                <div id="serialDetailsContainer" style="display:none; border:1px solid #ccc; padding:10px; border-radius:6px;">
                    <h6>Enter Details for Each Unit</h6>

                    <div id="serialRepeater"></div>

                    <button type="button" class="btn btn-sm btn-success my-2" id="addSerialBtn">
                        + Add Item Detail
                    </button>

                    <small class="text-danger">
                        If delivered quantity is 10, you must add 10 serial entries.
                    </small>
                </div>


                <!-- <div class="form-group mb-3">
                        <label>Warranty</label>
                        <input class="form-control" type="text" name="warranty" id="warranty" placeholder="e.g. 12 months">
                    </div> -->

                    <div class="form-group mb-3">
                        <label>Notes / Description</label>
                        <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" id="status_change_btn" class="btn btn-primary">Submit</button>
                </div>
            </form>


            </div>
        </div>
    </div>

    
<!-- Delivery History Modal -->
<div class="modal fade" id="deliveryHistoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delivery History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="deliveryHistoryBody">
        <!-- Filled by AJAX -->
        <div class="text-center">Loading…</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <script src="{{ asset('js/asset_management/invoice.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
