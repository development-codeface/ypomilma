@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Expense Item</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.expenseitems.update', $expenseitem->id) }}">
            @csrf
            @method('PUT')

            {{-- Item Code --}}
            <div class="form-group">
                <label class="required">Item Code</label>
                <input type="text" name="item_code" class="form-control"
                    value="{{ old('item_code', $expenseitem->item_code) }}" required>
            </div>

            {{-- Category --}}
            <div class="form-group">
                <label class="required">Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select a Category</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" {{ old('category_id', $expenseitem->category_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Item Name --}}
            <div class="form-group">
                <label class="required">Item Name</label>
                <input type="text" name="item_name" class="form-control"
                    value="{{ old('item_name', $expenseitem->item_name) }}" required>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $expenseitem->description) }}</textarea>
            </div>

            <button class="btn btn-success min-w-200" type="submit">Update</button>
        </form>
    </div>
</div>

@endsection
