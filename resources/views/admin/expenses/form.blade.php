
        {{-- Category --}}
        <div class="form-group">
            <label class="required">Category</label>
            <select name="expensecategory_id" id="category" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}"
                        {{ old('expensecategory_id', $expense->expensecategory_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Item --}}
        <div class="form-group">
            <label class="required">Item</label>
            <select name="expense_item" id="item" class="form-control" required>
                <option value="">Select Item</option>
                @if(!empty($expense->expense_item))
                    <option value="{{ $expense->expense_item }}" selected>{{ $expense->expense_item }}</option>
                @endif
            </select>
        </div>

        {{-- Rate --}}
        <div class="form-group">
            <label class="required">Rate</label>
            <input type="number" step="0.01" name="rate" id="rate" class="form-control"
                value="{{ old('rate', $expense->rate ?? '') }}" required>
        </div>

        {{-- Quantity --}}
        <div class="form-group">
            <label class="required">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control"
                value="{{ old('quantity', $expense->quantity ?? '') }}" required>
        </div>

        {{-- Amount --}}
        <div class="form-group">
            <label class="required">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                value="{{ old('amount', $expense->amount ?? '') }}" readonly required>
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description', $expense->description ?? '') }}</textarea>
        </div>
    </div>
</div>

<script>
document.getElementById('category').addEventListener('change', function() {
    let categoryId = this.value;
    let itemDropdown = document.getElementById('item');
    itemDropdown.innerHTML = '<option>Loading...</option>';

    if (categoryId) {
        fetch(`/expenses/items/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                itemDropdown.innerHTML = '<option value="">Select Item</option>';
                data.forEach(item => {
                    itemDropdown.innerHTML += `<option value="${item.item_code}">${item.name}</option>`;
                });
            });
    }
});

document.getElementById('quantity').addEventListener('input', calculateAmount);
document.getElementById('rate').addEventListener('input', calculateAmount);

function calculateAmount() {
    let rate = parseFloat(document.getElementById('rate').value) || 0;
    let qty = parseFloat(document.getElementById('quantity').value) || 0;
    document.getElementById('amount').value = (rate * qty).toFixed(2);
}
</script>
