@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Expense Categories
    </div>

    <div class="card-body">
        <a href="{{ route('admin.expense_categories.create') }}" class="btn btn-success mb-2">Create Category</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="{{ route('admin.expense_categories.edit', $category->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('admin.expense_categories.show', $category->id) }}" class="btn btn-info">Show</a>
                            <form action="{{ route('admin.expense_categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
