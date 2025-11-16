@extends('layouts.admin')
@section('content')
    <div class="card w_120">
        <div class="card-header">
            <p><i class="fi fi-br-plus-small mr_15_icc"></i> Create Invoice</p>
        </div>
        {{-- {{ route('admin.asset-management.invoice.store') }} --}}
        <div class="card-body col-md-12">
            <form method="POST" id="invoiceForm" action="" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Dairy --}}
                    {{-- <div class="form-group">
                        <label class="required"
                            for="name">{{ trans('cruds.asset_management.form_fields.name') }}</label>
                        <input class="form-control " type="text" name="name" id="name"
                            value="{{ old('name', '') }}">
                    </div>
                    <div class="form-group">
                        <label class="required"
                            for="address">{{ trans('cruds.asset_management.form_fields.address') }}</label>
                        <textarea class="form-control " name="address" id="address" rows="3">{{ old('address', '') }}</textarea>

                    </div>
                    <div class="form-group">
                        <label class="required"
                            for="contact_no">{{ trans('cruds.asset_management.form_fields.contact_no') }}</label>
                        <input class="form-control" type="text" name="contact_no" id="contact_no"
                            value="{{ old('contact_no', '') }}">

                    </div> --}}

                    <div class="form-group">
                        <label for="agency_name">Agency Name</label>
                        <select class="form-control {{ $errors->has('agency_name') ? 'is-invalid' : '' }}"
                            name="agency_name" id="agency_name">
                            <option value="">-- Select Agency --</option>
                            @foreach ($agency_name as $agency)
                                <option value="{{ $agency->id }}"
                                    {{ old('agency_name') == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('agency_name'))
                            <div class="invalid-feedback">{{ $errors->first('agency_name') }}</div>
                        @endif
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
                                        <select name="items[0][asset_id]" class="form-control product-select">
                                            <option value="">Select Product</option>
                                            @foreach ($assets as $asset)
                                                <option value="{{ $asset->id }}">{{ $asset->product->productname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[0][quantity]" class="form-control qty"
                                            value="1" min="1" id=""></td>
                                    <td><input type="number" name="items[0][unit_price]" class="form-control price"
                                            value="0" step="0.01"></td>
                                    <td><input type="number" name="items[0][gst_percent]" class="form-control gst"
                                            value="0" step="0.01"></td>
                                    <td>
                                        <select name="items[0][tax_type]" class="form-control tax-type">
                                            <option value="exclusive">Exclusive</option>
                                            <option value="inclusive">Inclusive</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[0][discount]" class="form-control discount"
                                            value="0" step="0.01"></td>
                                    <td><input type="text" name="items[0][total]" class="form-control total" readonly>
                                    </td>
                                    <td><button type="button" class="btn btn-danger removeRow">X</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-sm btn-secondary" id="addRow">
                            + Add Item
                        </button>

                    </div>

                    <div class="col-md-12 mt-4 text-end">
                        <button class="btn btn-success min-w-200" type="button" id="saveInvoiceBtn">
                            Save Invoice
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/asset_management/invoice.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('scripts')
    <script>
        let itemIndex = 1;

        function calculateRowTotal(row) {
            console.log('Calculating total for row:', row);
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
                    if (e.target.classList.contains('product-select') || e.target.classList.contains(
                            'qty') ||
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

        document.getElementById('addRow').addEventListener('click', function() {
            const table = document.querySelector('#itemsTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td>
            <select name="items[${itemIndex}][asset_id]" class="form-control product-select" >
                <option value="">Select Product</option>
                  @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->product->productname }} </option>
                  @endforeach
            </select>
        </td>
        <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" value="1" min="1"></td>
        <td><input type="number" name="items[${itemIndex}][unit_price]" class="form-control price" value="0" step="0.01" ></td>
         <td><input type="number" name="items[${itemIndex}][gst_percent]" class="form-control gst" value="0" step="0.01" ></td>
        <td>
            <select name="items[${itemIndex}][tax_type]" class="form-control tax-type">
                <option value="exclusive">Exclusive</option>
                <option value="inclusive">Inclusive</option>
            </select>
        </td>
        <td><input type="number" name="items[${itemIndex}][discount]" class="form-control discount" value="0" step="0.01"></td>
        <td><input type="text" name="items[${itemIndex}][total]" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger removeRow">X</button></td>`;
            table.appendChild(newRow);
            attachRowListeners(newRow);
            itemIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
