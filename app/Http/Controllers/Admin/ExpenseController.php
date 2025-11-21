<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ExpenseController extends Controller
{
   
    public function index(Request $request)
    {
        abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = auth()->user();

         $roleName = strtolower($user->role_name);
       
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


   public function create()
    {
         abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
         //   dd($productItems, $expenseItems); 

        $merged = $productItems->concat($expenseItems)->values();

        return response()->json($merged);
    }

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

        try {
            DB::beginTransaction();

            // /* -------------------------------------------------------
            //     GET DAIRY ID
            // --------------------------------------------------------*/
            // if ($roleName === 'superadmin') {
            //     // HO ID = 1
            //     $dairyId = 1;
            // } else {
                $dairy = \App\Models\Dairy::where('admin_userid', $user->id)->first();

                if (!$dairy) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'No dairy assigned to your account.');
                }

                $dairyId = $dairy->id;
            

            /* -------------------------------------------------------
                CHECK ACCOUNT BALANCE
            --------------------------------------------------------*/
            $account = \App\Models\Account::where('dairy_id', $dairyId)->first();

            if (!$account) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No account found for this dairy.');
            }

            if ($account->main_balance < $request->amount) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient balance to record this expense.');
            }

            // Deduct balance
            $account->main_balance -= $request->amount;
            $account->save();

            /* -------------------------------------------------------
                CREATE EXPENSE ENTRY
            --------------------------------------------------------*/
            $data['dairy_id'] = $dairyId;
            //$expense = \App\Models\Expense::create($data);
            $expense = new \App\Models\Expense($data);
            $expense->save();
            //dd($expense->id);

            /* -------------------------------------------------------
                CREATE TRANSACTION ENTRY
            --------------------------------------------------------*/
            \App\Models\Transactions::create([
                'dairy_id'             => $dairyId,
                'fund_allocation_id'   => null,
                'expense_category_id'  => $expense->expensecategory_id,
                'expense_id'           => $expense->id,
                'type'                 => 'debit',
                'amount'               => $expense->amount,
                'reference_no'         => 'EXP-' . $expense->id,
                'description'          => 'Expense: ' . $expense->expense_item,
                'status'               => 'completed',
                'transaction_date'     => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.expenses.index')
                ->with('success', 'Expense added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while saving the expense: ' . $e->getMessage());
        }
    }

    public function edit(Expense $expense)
    {
         abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = DB::table('expense_categories')->pluck('name', 'id');
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

   
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expensecategory_id' => 'required',
            'expense_item'       => 'required',
            'rate'               => 'required|numeric',
            'quantity'           => 'required|numeric',
            'amount'             => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $oldAmount = $expense->amount;
            $newAmount = $request->amount;
            $dairyId   = $expense->dairy_id;

            // Get account
            $account = \App\Models\Account::where('dairy_id', $dairyId)->first();

            if (!$account) {
                DB::rollBack();
                return back()->with('error', 'Account not found for this dairy.');
            }

            /* -----------------------------------------------------
                STEP 1: Restore old amount (add it back)
            ----------------------------------------------------- */
            $account->main_balance += $oldAmount;

            /* -----------------------------------------------------
                STEP 2: Check if new amount is available
            ----------------------------------------------------- */
            if ($account->main_balance < $newAmount) {
                DB::rollBack();
                return back()->with('error', 'Insufficient balance to update this expense.');
            }

            /* -----------------------------------------------------
                STEP 3: Deduct new amount
            ----------------------------------------------------- */
            $account->main_balance -= $newAmount;
            $account->save();

            /* -----------------------------------------------------
                STEP 4: Update Expense
            ----------------------------------------------------- */
            $expense->update($request->all());

            /* -----------------------------------------------------
                STEP 5: Update Transaction (if exists)
            ----------------------------------------------------- */
            $transaction = \App\Models\Transactions::where('reference_no', $expense->reference_no)->first();

            if ($transaction) {
                $transaction->update([
                    'amount'           => $newAmount,
                    'expense_category_id' => $request->expensecategory_id,
                    'description'      => 'Expense Updated: ' . $request->expense_item,
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.expenses.index')
                ->with('success', 'Expense updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }


    
    public function show(Expense $expense)
    {
        return view('admin.expenses.show', compact('expense'));
    }

  
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
