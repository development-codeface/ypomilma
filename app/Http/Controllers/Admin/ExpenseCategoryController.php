<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Gate;

class ExpenseCategoryController extends Controller
{
    

    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('admin.expense_categories.index', compact('categories'));
    }

    public function create()
    {
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
        return view('admin.expense_categories.show', compact('expenseCategory'));
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return redirect()->route('admin.expense_categories.index')->with('success', 'Category deleted successfully');
    }
}
