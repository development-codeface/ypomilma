@extends('layouts.admin')
@section('content')
    <style>
        td .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <p> <i class="fi fi-br-list mr_15_icc"></i> Vendors List</p>
                @can('vendor_create')
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-success lh20" href="{{ route('admin.vendors.create') }}">
                                <i class="fi fi-br-plus-small mr_5"></i> Add Vendor
                            </a>
                        </div>
                    </div>
                @endcan
         </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->id }}</td>
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td>
                                    @if($vendor->status)
                                        <span class="badge badge-info">Active</span>
                                    @else
                                        <span class="badge badge-info">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @can('vendor_show')
                                            <a class="btn btn-xs btn-primary"  href="{{ route('admin.vendors.show', $vendor->id) }}">
                                                <i class="fi fi-br-eye"></i>
                                            </a>
                                        @endcan
                                        @can('vendor_edit')
                                            <a class="btn btn-xs btn-info" href="{{ route('admin.vendors.edit', $vendor->id) }}">
                                                <i class="fi fi-br-list"></i>
                                            </a>
                                        @endcan

                                        <form action="{{ route('admin.vendors.toggleStatus', $vendor->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-xs btn-warning">
                                                @if($vendor->status)
                                                    <i class="fi fi-br-ban"></i> Deactivate
                                                @else
                                                    <i class="fi fi-br-check"></i> Activate
                                                @endif
                                            </button>
                                        </form>

                                        @can('vendor_delete')
                                            <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-xs btn-danger">
                                                    <i class="fi fi-br-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
