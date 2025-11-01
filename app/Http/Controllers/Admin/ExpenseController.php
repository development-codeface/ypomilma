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
    public function index(Request $request)
    {
        $user = auth()->user();

         $roleName = strtolower($user->role_name); // "superadmin", "admin", etc.
       
        $query = \App\Models\Expense::with(['dairy', 'category'])->latest();

        if ($roleName === 'superadmin') {
            if ($request->filled('dairy_id')) {
                $query->where('dairy_id', $request->dairy_id);
            }
        } else {
            $dairy = \App\Models\Dairy::where('admin_userid', $user->id)->first();
            if ($dairy) {
                $query->where('dairy_id', $dairy->id);
            } else {
                $query->whereNull('dairy_id');
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

         if ($request->filled('expensecategory_id')) {
            $query->where('expensecategory_id', $request->expensecategory_id);
        }

          $expenses = $query->paginate(10);

        $dairies = \App\Models\Dairy::orderBy('name')->get();
        $categories = \App\Models\ExpenseCategory::orderBy('name')->get();

        return view('admin.expenses.index', compact('expenses', 'dairies', 'categories'));
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
            'expense_item'       => 'required',
            'rate'               => 'required|numeric',
            'quantity'           => 'required|numeric',
            'amount'             => 'required|numeric',
        ]);

        $user = auth()->user();
        $roleName = strtolower($user->role_name); 
        $data = $request->all();

        if ($roleName === 'superadmin') {
            $data['is_headoffice'] = 1;
            $expense = \App\Models\Expense::create($data);
        } 
        else {
            $dairy = \App\Models\Dairy::where('admin_userid', $user->id)->first();

            if (!$dairy) {
                return redirect()->back()->with('error', 'No dairy assigned to your account.');
            }

            $account = \App\Models\Account::where('dairy_id', $dairy->id)->first();

            if (!$account) {
                return redirect()->back()->with('error', 'No account found for this dairy.');
            }

            $amount = $request->amount;

            if ($account->main_balance < $amount) {
                return redirect()->back()->with('error', 'Insufficient main balance to record this expense.');
            }

            $account->main_balance -= $amount;
            $account->save();

            $data['dairy_id'] = $dairy->id;
            $expense = \App\Models\Expense::create($data);

            \App\Models\Transactions::create([
                'dairy_id'             => $dairy->id,
                'fund_allocation_id'   => null, 
                'expense_category_id'  => $expense->expensecategory_id,
                'type'                 => 'debit',
                'amount'               => $amount,
                'reference_no'         => 'EXP-' . strtoupper(uniqid()),
                'description'          => 'Expense: ' . $expense->expense_item,
                'status'               => 'completed',
                'transaction_date'     => now(),
            ]);
        }

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense added successfully.');
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
