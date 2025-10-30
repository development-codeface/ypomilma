@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-eye mr_15_icc"></i> View Expense</p>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
        <tr><th>ID</th><td>{{ $expense->id }}</td></tr>
        <tr><th>Dairy</th><td>{{ $expense->dairy->name ?? '-' }}</td></tr>
        <tr><th>Category</th><td>{{ $expense->category->name ?? '-' }}</td></tr>
        <tr><th>Expense Item Code</th><td>{{ $expense->expense_item }}</td></tr>
        <tr><th>Expense Item </th><td>{{ $expense->item_name }}</td></tr>
        <tr><th>Rate</th><td>{{ $expense->rate }}</td></tr>
        <tr><th>Qty</th><td>{{ $expense->quantity }}</td></tr>
        <tr><th>Amount</th><td>{{ $expense->amount }}</td></tr>
        <tr><th>Description</th><td>{{ $expense->description }}</td></tr>
        <tr><th>Created At</th><td>{{ $expense->created_at->format('d-m-Y h:i A') }}</td></tr>
    </table>

    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

@endsection

