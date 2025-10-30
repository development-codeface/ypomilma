@extends('layouts.admin')

@section('content')

<div class="card w_90">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fi fi-br-list mr_15_icc"></i> Expense Items</h5>
        <a href="{{ route('admin.expenseitems.create') }}" class="btn btn-success">+ Add Expense Item</a>
    </div>

    <div class="card-body">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.expenseitems.index') }}" class="mb-3">
            <div class="row g-2 align-items-end">

                {{-- Category Filter --}}
                <div class="col-md-4">
                    <label>Filter by Category</label>
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $name }}" {{ request('category') == $name ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.expenseitems.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        {{-- Expense Items Table --}}
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenseItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category?->name ?? '-' }}</td>
                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.expenseitems.show', $item->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('admin.expenseitems.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.expenseitems.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No expense items found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $expenseItems->links() }}
        </div>
    </div>
</div>

@endsection
