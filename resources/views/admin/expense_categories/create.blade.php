@extends('layouts.admin')
@section('content')
<div class="w_100_bg">
    <div class="card w_90">
        <div class="card-header">
            Create Expense Category
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.expense_categories.store') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-lg-6 col-md-12">
                        <label for="name" class="required">Category Name</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name" value="{{ old('name') }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-lg-4 col-md-12 mar_top30">
                        <button type="submit" class="btn btn-success min-w-200">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
