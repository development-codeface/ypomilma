<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseItem;
use App\Models\Vendor;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ExpenseItemController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('expenseitem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

      $categoryFilter = $request->input('category');

        $expenseItems = ExpenseItem::with(['category'])
            ->when($categoryFilter, function ($query, $categoryFilter) {
                $query->whereHas('category', function ($subQuery) use ($categoryFilter) {
                    $subQuery->where('name', 'like', '%' . $categoryFilter . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        
        $categories = ExpenseCategory::pluck('name', 'id');

        return view('admin.expenseitems.index', compact('expenseItems', 'categories', 'categoryFilter'));
    }

    public function create()
    {
        abort_if(Gate::denies('expenseitem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ExpenseCategory::pluck('name', 'id');
        $vendors = Vendor::pluck('name', 'id');

        return view('admin.expenseitems.create', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'item_code'   => 'required|string|max:100|unique:expense_items,item_code',
            'category_id' => 'nullable|exists:expense_categories,id',
            'vendor_id'   => 'nullable|exists:vendors,id',
            'description' => 'nullable|string',
        ]);

        ExpenseItem::create($validated);

        return redirect()
            ->route('admin.expenseitems.index')
            ->with('success', 'Expense item created successfully.');
    }

    public function show(ExpenseItem $expenseitem)
    {
        abort_if(Gate::denies('expenseitem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenseitem->load(['category', 'vendor']);

        return view('admin.expenseitems.show', compact('expenseitem'));
    }

    public function edit(ExpenseItem $expenseitem)
    {
        abort_if(Gate::denies('expenseitem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ExpenseCategory::pluck('name', 'id');
        $vendors = Vendor::pluck('name', 'id');

        return view('admin.expenseitems.edit', compact('expenseitem', 'categories', 'vendors'));
    }

    public function update(Request $request, ExpenseItem $expenseitem)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'item_code'   => 'required|string|max:100|unique:expense_items,item_code,' . $expenseitem->id,
            'category_id' => 'nullable|exists:expense_categories,id',
            'vendor_id'   => 'nullable|exists:vendors,id',
            'description' => 'nullable|string',
        ]);

        $expenseitem->update($validated);

        return redirect()
            ->route('admin.expenseitems.index')
            ->with('success', 'Expense item updated successfully.');
    }

    public function destroy(ExpenseItem $expenseitem)
    {
        abort_if(Gate::denies('expenseitem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenseitem->delete();

        return redirect()
            ->route('admin.expenseitems.index')
            ->with('success', 'Expense item deleted successfully.');
    }
}
