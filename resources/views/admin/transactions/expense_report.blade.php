@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <p><i class="fi fi-br-receipt mr_15_icc"></i> Expense Statement</p>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('admin.expensereport.index') }}" class="mb-4">
            <div class="row g-3">
                @php
                    $user = auth()->user();
                    $roleName = strtolower($user->role_name);
                @endphp

                {{-- SUPERADMIN: show dairy filter --}}
                @if ($roleName === 'superadmin')
                    <div class="col-md-3">
                        <label>Dairy</label>
                        <select name="dairy_id" class="form-control">
                            <option value="">All Dairies</option>
                            @foreach ($dairies as $dairy)
                                <option value="{{ $dairy->id }}" {{ request('dairy_id') == $dairy->id ? 'selected' : '' }}>
                                    {{ $dairy->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-3">
                    <label>Expense Category</label>
                    <select name="expensecategory_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('expensecategory_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Search</button>

                    <a href="{{ route('admin.expenses.export', request()->query()) }}"
                        class="btn btn-success">
                        Download
                    </a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Dairy</th>
                    <th>Expense Category</th>
                    <th>Expense Item</th>
                    <th class="text-end">Amount (₹)</th>
                    <th>Description</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($expenses as $exp)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($exp->created_at)->format('d M Y') }}</td>
                        <td>{{ $exp->dairy->name ?? '-' }}</td>
                        <td>{{ $exp->category->name ?? '-' }}</td>
                        <td>{{ $exp->expense_item ?? '-' }}</td>
                        <td class="text-end">{{ number_format($exp->amount, 2) }}</td>
                        <td>{{ $exp->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No expenses found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $expenses->links() }}
    </div>
</div>

@endsection
