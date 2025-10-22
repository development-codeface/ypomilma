@extends('layouts.admin')
@section('content')
  <style>
        td .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
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
                    <th>Item Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Vendor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->item_code }}</td>
                        <td>{{ $product->productname }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->brand ?? '-'}}</td>
                        <td>{{ $product->model ?? '-'}}</td>
                        <!-- <td>
                            @if($product->img)
                                <img src="{{ asset('storage/'.$product->img) }}" width="60">
                            @else
                                —
                            @endif
                        </td> -->
                        <td>{{ $product->vendor->name }}</td>
                        <td>
                             <div class="action-buttons">
                             @can('product_show')
                              <a class="btn btn-xs btn-primary" href="{{ route('admin.products.show', $product) }}" > <i class="fi fi-br-eye"></i></a>
                              @endcan
                             @can('product_edit')
                              <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', $product) }}" > <i class="fi fi-br-list"></i></a>
                              @endcan
                              @can('product_delete')
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-secondary" onclick="return confirm('Delete this product?')">
                                    <i class="fi fi-br-trash"></i></button>
                            </form>
                             @endcan
                        </td>
                    </div>
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
