@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-edit mr_15_icc"></i> Edit Product</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="productname" class="form-control" value="{{ old('productname', $product->productname) }}" required>
            </div>

            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
            </div>

            <div class="form-group">
                <label>Model</label>
                <input type="text" name="model" class="form-control" value="{{ old('model', $product->model) }}">
            </div>

             <div class="form-group">
                <label>Vendor</label>
                <select name="vendor_id" class="form-control" required>
                    <option value="">Select a Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" 
                            {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Image</label>
                @if($product->img)
                    <div class="mb-2"><img src="{{ asset('storage/'.$product->img) }}" width="100"></div>
                @endif
                <input type="file" name="img" class="form-control">
            </div>

            <button class="btn btn-success min-w-200" type="submit">Update</button>
        </form>
    </div>
</div>

@endsection
