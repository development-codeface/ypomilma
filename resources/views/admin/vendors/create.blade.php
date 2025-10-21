@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Add Vendor </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.vendors.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">Vendor Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="phone">Phone</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone') }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" name="address" id="address">{{ old('address') }}</textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-success min-w-200" type="submit">Save Vendor</button>
            </div>
        </form>
    </div>
</div>

@endsection
