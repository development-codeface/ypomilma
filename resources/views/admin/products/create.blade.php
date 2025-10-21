@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Create Product</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="required" for="productname">Product Name</label>
                <input type="text" name="productname" class="form-control" value="{{ old('productname') }}" required>
            </div>

            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
            </div>

            <div class="form-group">
                <label>Model</label>
                <input type="text" name="model" class="form-control" value="{{ old('model') }}">
            </div>

             <div class="form-group">
                <label>Vendor</label>
                <select name="vendor_id" class="form-control" required>
                    <option value="">Select a Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Image</label>
                <input type="file" name="img" class="form-control">
            </div>

            <button class="btn btn-success min-w-200" type="submit">Save</button>
        </form>
    </div>
</div>

@endsection
