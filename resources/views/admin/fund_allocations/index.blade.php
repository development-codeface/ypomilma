@extends('layouts.admin')

@section('content')
<style>
    .filter-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }
    .filter-section .form-group {
        margin-right: 15px;
    }
    td .action-buttons {
        display: flex;
        gap: 5px;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fi fi-br-list mr_10_icc"></i> Fund Allocations</h5>
        <a href="{{ route('admin.fund_allocations.create') }}" class="btn btn-success">
            <i class="fi fi-br-plus-small mr_5"></i> Add Fund
        </a>
    </div>

    <div class="card-body">
        {{-- FILTER SECTION --}}
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.fund_allocations.index') }}" class="form-inline row align-items-end">
                
                {{-- Dairy --}}
                <div class="form-group col-md-4">
                    <label for="dairy_id" class="form-label">Dairy</label>
                    <select name="dairy_id" id="dairy_id" class="form-control w-100">
                        <option value="">-- All Dairies --</option>
                        @foreach ($dairies as $dairy)
                            <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                                {{ $dairy->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Financial Year --}}
                <div class="form-group col-md-4">
                    <label for="financial_year" class="form-label">Financial Year</label>
                    <select name="financial_year" id="financial_year" class="form-control w-100">
                        <option value="">-- All Years --</option>
                        @foreach ($financialYears as $year)
                            <option value="{{ $year }}" {{ request('financial_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fi fi-br-filter mr_5"></i> Filter
                    </button>
                    @if(request('financial_year') || request('dairy_id'))
                        <a href="{{ route('admin.fund_allocations.index') }}" class="btn btn-secondary">
                            <i class="fi fi-br-rotate-left mr_5"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
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
                            <td>
                                <span class="badge badge-{{ $allocation->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                            </td>
                            <td>{{ $allocation->remarks ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.fund_allocations.show', $allocation->id) }}" class="btn btn-xs btn-info">
                                        <i class="fi fi-br-eye"></i>
                                    </a>
                                     <!-- Edit Button -->
                                    <a href="{{ route('admin.fund_allocations.edit', $allocation->id) }}" 
                                        class="btn btn-xs btn-warning">
                                        <i class="fi fi-br-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.fund_allocations.adjust', $allocation->id) }}" 
                                        class="btn btn-xs btn-warning">
                                        <i class="fi fi-br-coins"></i>
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
