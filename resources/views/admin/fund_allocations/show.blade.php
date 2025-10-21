@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-eye mr_15_icc"></i> Fund Allocation Details</p>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $allocation->id }}</td>
            </tr>
            <tr>
                <th>Dairy</th>
                <td>{{ $allocation->dairy->name }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{ $allocation->amount }}</td>
            </tr>
            <tr>
                <th>Allocation Date</th>
                <td>{{ $allocation->allocation_date }}</td>
            </tr>
            <tr>
                <th>Financial Year</th>
                <td>{{ $allocation->financial_year }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($allocation->status) }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $allocation->remarks }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fund_allocations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
