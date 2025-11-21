@extends('layouts.admin')

@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Adjust Fund (Add / Reduce)</p>
    </div>

    <div class="card-body">

        <h5><strong>Dairy:</strong> {{ $allocation->dairy->name }}</h5>
        <h5><strong>Financial Year:</strong> {{ $allocation->financial_year }}</h5>
        <h5><strong>Current Allocated Amount:</strong> {{ number_format($allocation->amount, 2) }}</h5>
        <hr>

        <form action="{{ route('admin.fund_allocations.updateAdjust', $allocation->id) }}" method="POST">
            @csrf

            {{-- Adjust Type --}}
            <div class="form-group">
                <label>Adjust Fund Type <span class="text-danger">*</span></label>
                <select name="adjust_type" class="form-control" required>
                    <option value="add">Add Fund</option>
                    <option value="reduce">Reduce Fund</option>
                </select>
                @error('adjust_type') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Amount --}}
            <div class="form-group">
                <label>Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
                @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Remarks --}}
            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" rows="3" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Update Fund</button>
        </form>

    </div>
</div>

@endsection
