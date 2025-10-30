@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Expense</p>
    </div>

    <div class="card-body">

    <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.expenses.form', ['expense' => $expense, 'categories' => $categories])
        
        <button type="submit" class="btn btn-success">Update Expense</button>
    </form>
    </div>
</div>

@endsection
