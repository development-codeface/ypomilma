@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <p><i class="fi fi-br-list mr_15_icc"></i> Aggency Sale Show List</p>
            {{-- <a href="{{ route('admin.asset-management.create') }}" class="btn btn-success">
                <i class="fi fi-br-plus-small mr_5"></i> Add
            </a> --}}
        </div>

        <div class="card-body">
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}
            {{-- 🔍 Filters --}}
            {{-- <form method="GET" action="{{ route('admin.aggency-sale.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label for="status">Name</label>
                        <select name="name" id="name" class="form-control">
                            <option value="">All</option>
                            @foreach ($agency_name as $agency_names)
                            <option value="{{ $agency_names->name  }}" {{ request('name') == $agency_names->name   ? 'selected' : '' }}>{{ $agency_names->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="from_date">From</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="to_date">To</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2 filter-btns">
                            <i class="fi fi-br-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.aggency-sale.index') }}" class="btn btn-secondary filter-btns">
                            <i class="fi fi-br-rotate-left"></i> Reset
                        </a>
                    </div>
                </div>
            </form> --}}

            {{-- 🧾 Invoice Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.aggency_sale.fields.product') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.quantity') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.price') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.discount') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.gst_percent') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.tax_type') }}</th>
                            <th>{{ trans('cruds.aggency_sale.fields.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aggencyShow as $value)
                            <tr>
                            <td>{{ $value->product->productname ?? 'N/A' }}</td>
                            <td>{{ $value->quantity ?? 'N/A' }}</td>
                            <td>{{ $value->price ?? 'N/A' }}</td>
                            <td>{{ $value->discount ?? 'N/A' }}</td>
                            <td>{{ $value->gst_percent ?? 'N/A' }}</td>
                            <td>{{ $value->tax_type ?? 'N/A' }}</td>
                            <td>{{ $value->total ?? 'N/A' }}

                                @if($value->units && $value->units->count() > 0)
                                    <br>
                                    <button class="btn btn-sm btn-primary mt-1 viewUnitsBtn"
                                            data-units='@json($value->units)'>
                                        View Unit Details
                                    </button>
                                @endif

                            </td>
                        </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Aggency List found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{-- <div class="d-flex justify-content-center mt-4">
                {{ $aggencySales->links('pagination::bootstrap-5') }}
            </div> --}}

        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="unitDetailsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Unit Details</h5>
       <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
           
      </div>

      <div class="modal-body" id="unitDetailsBody">
      </div>

    </div>
  </div>
</div>

@endsection
<script>
    document.addEventListener("click", function(e){
    if (e.target.classList.contains("viewUnitsBtn")) {

        let units = JSON.parse(e.target.dataset.units);
        let modalBody = document.getElementById("unitDetailsBody");

        modalBody.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Warranty</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    ${
                        units.map(u => `
                            <tr>
                                <td>${u.serial_no ?? '-'}</td>
                                <td>${u.brand ?? '-'}</td>
                                <td>${u.model ?? '-'}</td>
                                <td>${u.warranty ?? '-'}</td>
                                <td>${u.description ?? '-'}</td>
                            </tr>
                        `).join('')
                    }
                </tbody>
            </table>
        `;

        $('#unitDetailsModal').modal('show');
    }
});
</script>
