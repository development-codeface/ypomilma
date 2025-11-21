@extends('layouts.admin')

@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Fund Allocation</p>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.fund_allocations.update', $allocation->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Dairy --}}
            <div class="form-group">
                <label>Dairy <span class="text-danger">*</span></label>
                <select name="dairy_id" class="form-control" required>
                    <option value="">Select Dairy</option>
                    @foreach ($dairies as $dairy)
                        <option value="{{ $dairy->id }}"
                            {{ $allocation->dairy_id == $dairy->id ? 'selected' : '' }}>
                            {{ $dairy->name }}
                        </option>
                    @endforeach
                </select>
                @error('dairy_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Financial Year --}}
            <div class="form-group">
                <label>Financial Year <span class="text-danger">*</span></label>
                <input type="text" name="financial_year" value="{{ old('financial_year', $allocation->financial_year) }}"
                       class="form-control" required>
                @error('financial_year') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Amount --}}
            <div class="form-group">
                <label>Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control"
                       value="{{ old('amount', $allocation->amount) }}" required>
                @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Allocation Date --}}
            <div class="form-group">
                <label>Allocation Date <span class="text-danger">*</span></label>
                <input type="date" name="allocation_date" class="form-control"
                       value="{{ old('allocation_date', $allocation->allocation_date) }}" required>
                @error('allocation_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Remarks --}}
            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $allocation->remarks) }}</textarea>
                @error('remarks') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Fund Allocation</button>

        </form>
    </div>
</div>

@endsection
