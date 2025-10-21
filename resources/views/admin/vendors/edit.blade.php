@extends('layouts.admin')

@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Vendor</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.vendors.update', $vendor->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="required">Vendor Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="required">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', $vendor->address) }}</textarea>
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="status" class="required">Status</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value="1" {{ old('status', $vendor->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $vendor->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    <i class="fi fi-br-save"></i> Save
                </button>
                
            </div>
        </form>
    </div>
</div>

@endsection
