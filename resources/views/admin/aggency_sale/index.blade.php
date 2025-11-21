@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Agency Sale List</p>
            {{-- <a href="{{ route('admin.asset-management.create') }}" class="btn btn-success">
                <i class="fi fi-br-plus-small mr_5"></i> Add
            </a> --}}
        </div>

        <div class="card-body">
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}
            {{-- 🔍 Filters --}}
            <form method="GET" action="{{ route('admin.aggency-sale.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label for="status">Name</label>
                        <select name="name" id="name" class="form-control">
                            <option value="">All</option>
                            @foreach($agency_name as $agency_names)
                            <option value="{{ $agency_names->id  }}" {{ request('name') == $agency_names->id   ? 'selected' : '' }}>{{ $agency_names->name }}
                            </option>
                            @endforeach
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
                        <a href="{{ route('admin.aggency-sale.index') }}" class="btn btn-secondary filter-btns">
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
                            <th>{{ trans('cruds.aggency_sale.fields.id') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.invoice_id') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.name') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.contact_no') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.total_amount') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.created_date') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php
                            $i = 1;
                        @endphp
                         @forelse($aggencySales as $value)
                            <tr>
                                 <td>{{ $i++ }}</td>
                                <td>{{ $value->invoice_id ?? 'N/A' }}</td>
                                <td>{{ $value->agency->name ?? 'N/A' }}</td>
                                <td>{{ $value->agency->contact_no ?? 'N/A' }}</td>
                                <td>{{ $value->total_amount ?? 'N/A' }}</td>
                                <td>{{ $value->created_at ?? 'N/A' }}</td>
                                <td>
                                      <a href="{{ route('admin.aggency-sale.show', $value->id) }}" class="btn btn-xs btn-primary">
                                    <i class="fi fi-br-eye"></i>
                                </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Aggency List found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $aggencySales->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
