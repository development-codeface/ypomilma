@extends('layouts.admin')

@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Create Expense</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.expenses.store') }}">
            @csrf

            {{-- Category --}}
            <div class="form-group">
                <label class="required">Category</label>
                <select id="category_id" name="expensecategory_id" class="form-control" required>
                    <option value="">Select a Category</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" {{ old('expensecategory_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Item --}}
            <div class="form-group">
                <label class="required">Item</label>
                <select id="item_select" name="expense_item" class="form-control" required>
                    <option value="">Select Item</option>
                </select>
                <small class="text-muted">Items are loaded automatically based on the selected category.</small>
            </div>

            
            {{-- Rate --}}
            <div class="form-group">
                <label class="required">Rate</label>
                <input type="number" step="0.01" name="rate" id="rate" class="form-control"
                    value="" required>
            </div>

            {{-- Quantity --}}
            <div class="form-group">
                <label class="required">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                    value="1" required>
            </div>

            {{-- Amount --}}
            <div class="form-group">
                <label class="required">Amount</label>
                <input type="number" name="amount" class="form-control" step="0.01"
                    value="{{ old('amount') }}" required>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
            </div>

            <button class="btn btn-success min-w-200" type="submit">Save Expense</button>
        </form>
    </div>
</div>

{{-- Script for Dynamic Item Loading --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const itemSelect = document.getElementById('item_select');

    categorySelect.addEventListener('change', function () {
        const categoryId = this.value;
        itemSelect.innerHTML = '<option value="">Loading...</option>';

        if (!categoryId) {
            itemSelect.innerHTML = '<option value="">Select Item</option>';
            return;
        }

        fetch(`/admin/expenses/items/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                itemSelect.innerHTML = '<option value="">Select Item</option>';
                if (data.length > 0) {
                    data.forEach(item => {
                        const label = `${item.name} `;
                        itemSelect.innerHTML += `<option value="${item.item_code}">${label}</option>`;
                    });
                } else {
                    itemSelect.innerHTML = '<option value="">No items found</option>';
                }
            })
            .catch(err => {
                console.error(err);
                itemSelect.innerHTML = '<option value="">Error loading items</option>';
            });
    });
});
</script>

@endsection
