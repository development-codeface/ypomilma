<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendors = Vendor::all();
        $categories = ExpenseCategory::all();
        return view('admin.products.create', compact('vendors', 'categories'));
      
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productname' => 'required|string|max:255',
            'item_code' => 'required|string|max:100',
            'category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string',
            'vendor_id' => 'required|integer',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $vendors = Vendor::all();
        $categories = ExpenseCategory::all();
        return view('admin.products.edit', compact('product', 'vendors', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'productname' => 'required|string|max:255',
            'description' => 'required|string',
            'vendor_id' => 'required|integer',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($product->img && file_exists(storage_path('app/public/' . $product->img))) {
            unlink(storage_path('app/public/' . $product->img));
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
