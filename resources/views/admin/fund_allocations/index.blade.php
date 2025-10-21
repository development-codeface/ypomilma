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
        <p><i class="fi fi-br-list mr_15_icc"></i> Fund Allocations</p>

        <div class="row align-items-center mb-2">
            <div class="col-md-9">
                <form method="GET" action="{{ route('admin.fund_allocations.index') }}" class="form-inline">

                    {{-- Dairy Filter --}}
                    <label for="dairy_id" class="mr-2">Dairy:</label>
                    <select name="dairy_id" id="dairy_id" class="form-control mr-3">
                        <option value="">-- All Dairies --</option>
                        @foreach ($dairies as $dairy)
                            <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                                {{ $dairy->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Financial Year Filter --}}
                    <label for="financial_year" class="mr-2">Financial Year:</label>
                    <select name="financial_year" id="financial_year" class="form-control mr-3">
                        <option value="">-- All Years --</option>
                        @foreach ($financialYears as $year)
                            <option value="{{ $year }}" {{ request('financial_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Buttons --}}
                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                    @if(request('financial_year') || request('dairy_id'))
                        <a href="{{ route('admin.fund_allocations.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>
            </div>

            <div class="col-md-3 text-right">
                <a href="{{ route('admin.fund_allocations.create') }}" class="btn btn-success">
                    <i class="fi fi-br-plus-small mr_5"></i> Add Fund 
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dairy</th>
                        <th>Amount</th>
                        <th>Allocation Date</th>
                        <th>Financial Year</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allocations as $allocation)
                        <tr>
                            <td>{{ $allocation->id }}</td>
                            <td>{{ $allocation->dairy->name ?? '-' }}</td>
                            <td>{{ number_format($allocation->amount, 2) }}</td>
                            <td>{{ $allocation->allocation_date }}</td>
                            <td>{{ $allocation->financial_year }}</td>
                            <td><span class="badge badge-success">{{ ucfirst($allocation->status) }}</span></td>
                            <td>{{ $allocation->remarks ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.fund_allocations.show', $allocation->id) }}"
                                       class="btn btn-xs btn-info">
                                        <i class="fi fi-br-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No fund allocations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $allocations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
