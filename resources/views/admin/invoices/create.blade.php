@extends('layouts.admin')
@section('content')

<div class="card w_120">
    <div class="card-header">
        <p><i class="fi fi-br-plus-small mr_15_icc"></i> Create Invoice</p>
    </div>

    <div class="card-body col-md-12">
        <form method="POST" action="{{ route('admin.invoices.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Dairy --}}
                <div class="form-group col-md-12">
                    <label class="required" for="dairy_id">Dairy</label>
                    <select name="dairy_id" id="dairy_id" class="form-control" required>
                        <option value="">Select Dairy</option>
                        @foreach($dairies as $dairy)
                            <option value="{{ $dairy->id }}">{{ $dairy->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Vendor --}}
                <div class="form-group col-md-12">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Invoice Items --}}
                <div class="col-md-12 mt-12">
                  <h5>Invoice Items</h5>
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>GST %</th>
                                <th>Tax Type</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="form-control">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->productname }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][quantity]" class="form-control qty" value="1" min="1"></td>
                                <td><input type="number" name="items[0][unit_price]" class="form-control price" value="0" step="0.01"></td>
                                <td><input type="number" name="items[0][gst_percent]" class="form-control gst" value="0" step="0.01"></td>
                                <td>
                                    <select name="items[0][tax_type]" class="form-control tax-type">
                                        <option value="exclusive">Exclusive</option>
                                        <option value="inclusive">Inclusive</option>
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][discount]" class="form-control discount" value="0" step="0.01"></td>
                                <td><input type="text" name="items[0][total]" class="form-control total" readonly></td>
                                <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-sm btn-secondary" id="addRow">
                        + Add Item
                    </button>

                </div>

                <div class="col-md-12 mt-4 text-end">
                    <button class="btn btn-success min-w-200" type="submit">
                        Save Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
let itemIndex = 1;

function calculateRowTotal(row) {
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const gst = parseFloat(row.querySelector('.gst').value) || 0;
    const discount = parseFloat(row.querySelector('.discount').value) || 0;
    const taxType = row.querySelector('.tax-type').value;

    let baseValue = (price * qty) - discount;
    let gstAmount = 0;
    let total = 0;

    if (taxType === 'inclusive') {
        const taxableValue = baseValue / (1 + gst / 100);
        gstAmount = baseValue - taxableValue;
        total = baseValue;
    } else {
        gstAmount = baseValue * (gst / 100);
        total = baseValue + gstAmount;
    }

    row.querySelector('.total').value = total.toFixed(2);
}

function attachRowListeners(row) {
    ['input', 'change'].forEach(evt => {
        row.addEventListener(evt, e => {
            if (e.target.classList.contains('qty') ||
                e.target.classList.contains('price') ||
                e.target.classList.contains('gst') ||
                e.target.classList.contains('discount') ||
                e.target.classList.contains('tax-type')) {
                calculateRowTotal(row);
            }
        });
    });
}

// Initial row
document.querySelectorAll('#itemsTable tbody tr').forEach(attachRowListeners);

document.getElementById('addRow').addEventListener('click', function () {
    const table = document.querySelector('#itemsTable tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <select name="items[${itemIndex}][product_id]" class="form-control">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->productname }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" value="1" min="1"></td>
        <td><input type="number" name="items[${itemIndex}][unit_price]" class="form-control price" value="0" step="0.01"></td>
        <td><input type="number" name="items[${itemIndex}][gst_percent]" class="form-control gst" value="0" step="0.01"></td>
        <td>
            <select name="items[${itemIndex}][tax_type]" class="form-control tax-type">
                <option value="exclusive">Exclusive</option>
                <option value="inclusive">Inclusive</option>
            </select>
        </td>
        <td><input type="number" name="items[${itemIndex}][discount]" class="form-control discount" value="0" step="0.01"></td>
        <td><input type="text" name="items[${itemIndex}][total]" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger removeRow">X</button></td>
    `;
    table.appendChild(newRow);
    attachRowListeners(newRow);
    itemIndex++;
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeRow')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
