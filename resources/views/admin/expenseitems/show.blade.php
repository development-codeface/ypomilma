@extends('layouts.admin')
@section('content')

<div class="card w_80">
    <div class="card-header">
        <p><i class="fi fi-br-eye mr_15_icc"></i> View Product</p>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <!-- <tr><th>ID</th><td>{{ $product->id }}</td></tr> -->
            <tr><th>Item Code</th><td>{{ $product->item_code }}</td></tr>
            <tr><th>Category</th><td>{{ $product->category->name ?? 'N/A' }}</td></tr>
            <tr><th>Item Name</th><td>{{ $product->productname }}</td></tr>
            <tr><th>Brand</th><td>{{ $product->brand  ?? '-' }}</td></tr>
            <tr><th>Model</th><td>{{ $product->model  ?? '-' }}</td></tr>
            <tr><th>Vendor ID</th><td><p>{{ $product->vendor->name ?? 'N/A' }}</p></td></tr>
            <tr><th>Description</th><td>{{ $product->description }}</td></tr>
            <tr><th>Image</th>
                <td>
                    @if($product->img)
                        <img src="{{ asset('storage/'.$product->img) }}" width="120">
                    @else —
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('admin.products.index') }}" class="btn btn-success min-w-200">Back to List</a>
    </div>
</div>

@endsection
