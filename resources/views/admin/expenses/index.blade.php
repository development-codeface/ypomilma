@extends('layouts.admin')

@section('content')

<div class="card w_90">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fi fi-br-list mr_15_icc"></i> Expenses</h5>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-success">+ Add Expense</a>
    </div>

    <div class="card-body">
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
                                    <a class="btn btn-xs btn-primary"
                                        href="{{ route('admin.expenses.show', $exp->id) }}">
                                        <i class="fi fi-br-eye"></i>
                                    </a>
                                @endcan

                                @can('expense_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.expenses.edit', $exp->id) }}">
                                        <i class="fi fi-br-list"></i>
                                    </a>
                                @endcan

                                <!-- @can('expense_delete')
                                    <form action="{{ route('admin.expenses.destroy', $exp->id) }}" method="POST"
                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fi fi-br-trash"></i>
                                        </button>
                                    </form>
                                @endcan -->
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No expenses found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
