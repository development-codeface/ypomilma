@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Assets</p>
            <a href="{{ route('admin.asset-management.create') }}" class="btn btn-success">
                <i class="fi fi-br-plus-small mr_5"></i> Add
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- 🔍 Filters --}}
            <form method="GET" action="{{ route('admin.asset-management.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    {{-- <div class="col-md-3">
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
                    </div> --}}

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
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold
                            </option>
                            <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Damaged
                            </option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
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
                        <a href="{{ route('admin.asset-management.index') }}" class="btn btn-secondary filter-btns">
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
                            <th>{{ trans('cruds.asset_management.fields.id') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.diary') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.product') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.quantity') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.purchase_rate') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.purchase_date') }}</th>
                            <th>{{ trans('cruds.asset_management.fields.status') }}</th>
                            {{-- <th>{{ trans('cruds.asset_management.fields.action') }}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @forelse($assets as $asset)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $asset->dairy->name ?? 'N/A' }}</td>
                                <td>{{ $asset->product->productname ?? 'N/A' }}</td>
                                <td>{{ $asset->quantity ?? 'N/A' }}</td>
                                <td>{{ $asset->purchase_value ?? 'N/A' }}</td>
                                <td>
                                    {{ $asset->purchase_date ?? 'N/A' }}
                                </td>
                                <td>
                                    @if ($asset->status == 'sold')
                                        <span class="badge bg-success">Sold</span>
                                    @elseif($asset->status == 'damaged')
                                        <span class="badge bg-danger">Damaged</span>
                                    @else
                                        <span class="badge bg-danger">Available</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    <button type="submit" class="btn btn-xs btn-warning">
                                        Add
                                    </button>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No invoices found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $assets->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
