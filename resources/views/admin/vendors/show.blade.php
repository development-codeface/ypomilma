@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-eye mr_15_icc"></i>
            {{ trans('global.show') }} Vendors
        </p>
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-success min-w-200" href="{{ route('admin.vendors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $vendor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Name
                        </th>
                        <td>
                            {{ $vendor->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $vendor->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Phone
                        </th>
                        <td>
                            {{ $vendor->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Address
                        </th>
                        <td>
                            {{ $vendor->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Status
                        </th>
                        <td>
                            <span class="badge {{ $vendor->status == 1 ? 'badge-success' : 'badge-danger' }}">
                                {{ $vendor->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-success min-w-200" href="{{ route('admin.vendors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
