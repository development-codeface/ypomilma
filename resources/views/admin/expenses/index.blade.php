@extends('layouts.admin')

@section('content')
<div class="card w_90">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fi fi-br-list mr_15_icc"></i> Expenses</h5>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-success">+ Add Expense</a>
    </div>

    <div class="card-body">

        {{-- 🔍 Filter Section --}}
        <form method="GET" action="{{ route('admin.expenses.index') }}" class="mb-3">
            <div class="row g-3 align-items-end">

                 @php
                    $user = auth()->user();
                    $roleName = strtolower($user->role_name);
                @endphp

                {{-- SUPERADMIN: show dairy filter --}}
                @if ($roleName === 'superadmin')
                    <div class="col-md-3">
                        <label for="dairy_id">Dairy</label>
                        <select name="dairy_id" id="dairy_id" class="form-control">
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
                    <label for="expensecategory_id">Category</label>
                    <select name="expensecategory_id" id="expensecategory_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('expensecategory_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        {{-- 🧾 Expense Table --}}
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Dairy</th>
                    <th>Amount</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $exp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $exp->expense_item }}</td>
                        <td>{{ $exp->item_name }}</td>
                        <td>{{ $exp->category->name ?? 'N/A' }}</td>
                        <td>{{ $exp->dairy->name ?? 'N/A' }}</td>
                        <td>{{ number_format($exp->amount, 2) }}</td>
                        <td>{{ $exp->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="action-buttons">
                                @can('expense_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.expenses.show', $exp->id) }}">
                                        <i class="fi fi-br-eye"></i>
                                    </a>
                                @endcan

                                @can('expense_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.expenses.edit', $exp->id) }}">
                                        <i class="fi fi-br-list"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No expenses found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $expenses->links() }}
    </div>
</div>
@endsection
