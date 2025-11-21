@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Agency List</p>
           
                <a href="{{ route('admin.aggency.create') }}" class="btn btn-success">
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
            {{-- <form method="GET" action="{{ route('admin.asset-management.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                        </select>
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
            </form> --}}

            {{-- 🧾 Invoice Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.agency.fields.id') }}</th>
                            <th>{{ trans('cruds.agency.fields.agency_code') }}</th>
                            <th>{{ trans('cruds.agency.fields.name') }}</th>
                            <th>{{ trans('cruds.agency.fields.email') }}</th>
                            <th>{{ trans('cruds.agency.fields.address') }}</th>
                            <th>{{ trans('cruds.agency.fields.contact_no') }}</th>
                            <th>{{ trans('cruds.agency.fields.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @forelse($agency as $value)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $value->agency_code ?? 'N/A' }}</td>
                                <td>{{ $value->name ?? 'N/A' }}</td>
                                <td>{{ $value->email ?? 'N/A' }}</td>
                                <td>{{ $value->address ?? 'N/A' }}</td>
                                <td>{{ $value->contact_no ?? 'N/A' }}</td>
                                <td>
                                    @can('agency_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.aggency.edit', $value->id) }}">
                                            <i class="fi fi-br-list"></i>
                                        </a>
                                    @endcan

                                    @can('agency_delete')
                                        <form action="{{ route('admin.aggency.destroy', $value->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                <i class="fi fi-br-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Agency found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $agency->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
