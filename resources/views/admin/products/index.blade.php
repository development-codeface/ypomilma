@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p><i class="fi fi-br-list mr_15_icc"></i> Product List</p>
          @can('product_create')
          <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Add Product</a>
           @endcan
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Image</th>
                    <th>Vendor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->productname }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>{{ $product->model }}</td>
                        <td>
                            @if($product->img)
                                <img src="{{ asset('storage/'.$product->img) }}" width="60">
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $product->vendor->name }}</td>
                        <td>
                             @can('product_show')
                             <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info"> <i class="fi fi-br-eye"></i></a>
                              @endcan
                             @can('product_edit')
                             <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary"> <i class="fi fi-br-list"></i></a>
                              @endcan
                              @can('product_delete')
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                                    <i class="fi fi-br-trash"></i></button>
                            </form>
                             @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $products->links() }}</div>
    </div>
</div>

@endsection
