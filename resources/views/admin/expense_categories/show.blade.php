@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Show Expense Category
    </div>

    <div class="card-body">
        <a class="btn btn-default" href="{{ route('admin.expense_categories.index') }}">
            Back to List
        </a>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $expenseCategory->id }}</td>
                </tr>
                <tr>
                    <th>Category Name</th>
                    <td>{{ $expenseCategory->name }}</td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-default" href="{{ route('admin.expense_categories.index') }}">
            Back to List
        </a>
    </div>
</div>
@endsection
