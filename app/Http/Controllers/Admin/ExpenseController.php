<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    // Show list
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        return view('admin.expenses.index', compact('expenses'));
    }

    // Show form
    public function create()
    {
        $categories = DB::table('expense_categories')->pluck('name', 'id');
        return view('admin.expenses.create', compact('categories'));
    }

    // Fetch items based on category
    public function getItemsByCategory($categoryId)
    {
        $productItems = Product::where('category_id', $categoryId)
            ->select('item_code', 'productname as name')
            ->get();

        $expenseItems = ExpenseItem::where('category_id', $categoryId)
            ->select('item_code', 'item_name as name')
            ->get();

        $merged = $productItems->merge($expenseItems)->values();

        return response()->json($merged);
    }

    // Store expense
    public function store(Request $request)
    {
        $request->validate([
            'expensecategory_id' => 'required',
            'expense_item' => 'required',
            'rate' => 'required|numeric',
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        Expense::create($request->all());

        return redirect()->route('admin.expenses.index')->with('success', 'Expense added successfully.');
    }

    // Edit
    public function edit(Expense $expense)
    {
        $categories = DB::table('expense_categories')->pluck('name', 'id');
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    // Update
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expensecategory_id' => 'required',
            'expense_item' => 'required',
            'rate' => 'required|numeric',
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $expense->update($request->all());

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    // Show single expense
    public function show(Expense $expense)
    {
        return view('admin.expenses.show', compact('expense'));
    }

    // Delete
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
