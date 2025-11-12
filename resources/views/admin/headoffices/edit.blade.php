@extends('layouts.admin')

@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Head Office Budget</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.headoffices.update', $headoffice->id) }}">
            @csrf
            @method('PUT')

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
                        $selectedYear = old('financial_year', $headoffice->financial_year ?? '');
                    @endphp
                    <option value="{{ $fy }}" {{ $selectedYear == $fy ? 'selected' : '' }}>
                        {{ $fy }}
                    </option>
                @endfor
            </select>
            @error('financial_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

            <div class="form-group">
                <label class="required">Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $headoffice->amount) }}" required>
            </div>

            <button class="btn btn-success min-w-200" type="submit">Update</button>
        </form>
    </div>
</div>

@endsection
