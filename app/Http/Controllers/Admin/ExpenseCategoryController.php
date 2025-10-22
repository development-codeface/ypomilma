<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ExpenseCategoryController extends Controller
{
    

    public function index()
    {
        abort_if(Gate::denies('expensecategory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = ExpenseCategory::all();
        return view('admin.expense_categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('expensecategory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.expense_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.expense_categories.index')->with('success', 'Category created successfully');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expensecategory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.expense_categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.expense_categories.index')->with('success', 'Category updated successfully');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expensecategory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.expense_categories.show', compact('expenseCategory'));
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        abort_if(Gate::denies('expensecategory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $expenseCategory->delete();
        return redirect()->route('admin.expense_categories.index')->with('success', 'Category deleted successfully');
    }
}
