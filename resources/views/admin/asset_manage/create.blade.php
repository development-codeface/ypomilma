@extends('layouts.admin')
@section('content')
    <div class="card w_120">
        <div class="card-header">
            <p><i class="fi fi-br-plus-small mr_15_icc"></i> Create Invoice</p>
        </div>

        <div class="card-body col-md-12">
            <form method="POST" id="invoiceForm" action="" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    {{-- Agency --}}
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
                                                <option value="{{ $asset->id }}">{{ $asset->product->productname }}</option>
                                            @endforeach
                                        </select>

                                        {{-- Button to open unit details modal --}}
                                        <button type="button" class="btn btn-sm btn-info mt-2 openUnitModal">
                                            + Add Unit Details
                                        </button>

                                        {{-- Hidden JSON field for unit details --}}
                                        <input type="hidden" name="items[0][units]" class="units-json">
                                    </td>

                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control qty"
                                               value="1" min="1">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" class="form-control price"
                                               value="0" step="0.01">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][gst_percent]" class="form-control gst"
                                               value="0" step="0.01">
                                    </td>
                                    <td>
                                        <select name="items[0][tax_type]" class="form-control tax-type">
                                            <option value="exclusive">Exclusive</option>
                                            <option value="inclusive">Inclusive</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][discount]" class="form-control discount"
                                               value="0" step="0.01">
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][total]" class="form-control total" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger removeRow">X</button>
                                    </td>
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

    {{-- SERIAL UNIT MODAL (Bootstrap 4) --}}
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Enter Item Unit Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body" id="unitModalBody">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-success saveUnitDetails">Save Details</button>
          </div>

        </div>
      </div>
    </div>

    <script src="{{ asset('js/asset_management/invoice.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script>
    let itemIndex = 1;

    // ===============================
    // TOTAL CALCULATION
    // ===============================
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
                if (
                    e.target.classList.contains('product-select') ||
                    e.target.classList.contains('qty') ||
                    e.target.classList.contains('price') ||
                    e.target.classList.contains('gst') ||
                    e.target.classList.contains('discount') ||
                    e.target.classList.contains('tax-type')
                ) {
                    calculateRowTotal(row);
                }
            });
        });
    }

    // Initial row
    document.querySelectorAll('#itemsTable tbody tr').forEach(attachRowListeners);

    // ===============================
    // ADD NEW ROW
    // ===============================
    document.getElementById('addRow').addEventListener('click', function() {
        const table = document.querySelector('#itemsTable tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="items[${itemIndex}][asset_id]" class="form-control product-select">
                    <option value="">Select Product</option>
                    @foreach ($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->product->productname }}</option>
                    @endforeach
                </select>

                <button type="button" class="btn btn-sm btn-info mt-2 openUnitModal">
                    + Add Unit Details
                </button>

                <input type="hidden" name="items[${itemIndex}][units]" class="units-json">
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

    // REMOVE ROW
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // ===============================
    // UNIT DETAILS POPUP
    // ===============================
    let currentUnitRow = null;

    // OPEN MODAL AND PREFILL IF JSON EXISTS
    document.addEventListener("click", function(e){
        if(e.target.classList.contains("openUnitModal")) {

            currentUnitRow = e.target.closest("tr");

            let qtyInput = currentUnitRow.querySelector(".qty");
            let qty = parseInt(qtyInput.value || 0);
            if (isNaN(qty) || qty <= 0) {
                qty = 1;
                qtyInput.value = 1;
            }

            let modalBody = document.getElementById("unitModalBody");
            let savedData = currentUnitRow.querySelector(".units-json").value;

            modalBody.innerHTML = "";

            let units = [];
            if (savedData) {
                try {
                    units = JSON.parse(savedData);
                } catch (err) {
                    units = [];
                }
            }

            for(let i = 0; i < qty; i++) {
                let serial   = units[i]?.serial_no ?? "";
                let brand    = units[i]?.brand ?? "";
                let model    = units[i]?.model ?? "";
                let warranty = units[i]?.warranty ?? "";

                modalBody.innerHTML += `
                    <div class="row border p-2 mb-2 unit-item">
                        <strong>Unit ${i+1}</strong>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Serial No" value="${serial}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Brand" value="${brand}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Model" value="${model}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Warranty" value="${warranty}">
                        </div>
                    </div>
                `;
            }

            // Show modal (Bootstrap 4)
            $('#unitModal').modal('show');
        }
    });

    // SAVE UNIT DETAILS & CLOSE MODAL
    document.addEventListener("click", function(e){
        if(e.target.classList.contains("saveUnitDetails")) {

            let unitRows = document.querySelectorAll("#unitModalBody .unit-item");
            let data = [];

            unitRows.forEach(r => {
                let inputs = r.querySelectorAll("input");
                data.push({
                    serial_no: inputs[0].value,
                    brand:     inputs[1].value,
                    model:     inputs[2].value,
                    warranty:  inputs[3].value,
                });
            });

            if (currentUnitRow) {
                currentUnitRow.querySelector(".units-json").value = JSON.stringify(data);
            }

            // Hide modal
            $('#unitModal').modal('hide');
        }
    });
</script>
@endsection
