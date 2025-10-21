@extends('layouts.admin')

@section('content')
<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Create Fund Allocation</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.fund_allocations.store') }}">
            @csrf

            {{-- Dairy --}}
            <div class="form-group">
                <label class="required" for="dairy_id">Dairy</label>
                <select class="form-control select2" name="dairy_id" id="dairy_id" required>
                    <option value="">-- Select Dairy --</option>
                    @foreach($dairies as $dairy)
                        <option value="{{ $dairy->id }}" {{ old('dairy_id') == $dairy->id ? 'selected' : '' }}>
                            {{ $dairy->name }}
                        </option>
                    @endforeach
                </select>
                @error('dairy_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Amount --}}
            <div class="form-group">
                <label class="required" for="amount">Amount</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                       value="{{ old('amount') }}" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Allocation Date --}}
            <div class="form-group">
                <label class="required" for="allocation_date">Allocation Date</label>
                <input type="date" class="form-control" name="allocation_date" id="allocation_date"
                       value="{{ old('allocation_date') }}" required>
                @error('allocation_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Financial Year --}}
            <div class="form-group">
                <label class="required" for="financial_year">Financial Year</label>
                <select class="form-control" name="financial_year" id="financial_year" required>
                    <option value="">-- Select Financial Year --</option>
                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 5;
                        $endYear = $currentYear + 5;
                    @endphp
                    @for($year = $startYear; $year <= $endYear; $year++)
                        @php
                            $next = $year + 1;
                            $fy = $year . '-' . $next;
                        @endphp
                        <option value="{{ $fy }}" {{ old('financial_year') == $fy ? 'selected' : '' }}>
                            {{ $fy }}
                        </option>
                    @endfor
                </select>
                @error('financial_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <!-- <div class="form-group">
                <label class="required" for="status">Status</label>
                <select class="form-control" name="status" id="status" required>
                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> -->

            {{-- Remarks --}}
            <div class="form-group">
                <label for="remarks">Remarks (Optional)</label>
                <textarea class="form-control" name="remarks" id="remarks" rows="3">{{ old('remarks') }}</textarea>
                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success min-w-200">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
