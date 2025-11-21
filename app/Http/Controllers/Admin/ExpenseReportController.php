<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Dairy;
use App\Models\ExpenseCategory;
use App\Exports\ExpenseExport;
use Maatwebsite\Excel\Facades\Excel;
use Gate;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
         abort_if(Gate::denies('expense_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = auth()->user();
        $roleName = strtolower($user->role_name);

        $expenses = collect();
        $dairies = Dairy::orderBy('name')->get();
        $categories = ExpenseCategory::orderBy('name')->get();

        // Base query
        $query = Expense::query();

        // -------------------------------------------------
        // ROLE LOGIC
        // -------------------------------------------------
        if ($roleName === 'superadmin') {

            if ($request->filled('dairy_id')) {
                $request->validate([
                    'dairy_id' => 'exists:dairies,id',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date',
                ]);

                $query->where('dairy_id', $request->dairy_id);
            }

        } else {
            // Dairy Admin → only their dairy
            $dairy = Dairy::where('admin_userid', $user->id)->first();

            if ($dairy) {
                $query->where('dairy_id', $dairy->id);
            } else {
                $query->whereNull('dairy_id');
            }
        }

        // -------------------------------------------------
        // COMMON FILTERS
        // -------------------------------------------------

        if ($request->filled('expensecategory_id')) {
            $query->where('expensecategory_id', $request->expensecategory_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('expense_item')) {
            $query->where('expense_item', 'like', "%{$request->expense_item}%");
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // -------------------------------------------------
        // EXECUTE QUERY
        // -------------------------------------------------

        if ($query->getQuery()->wheres) {
            $expenses = $query->with(['dairy', 'category'])
                              ->orderBy('created_at', 'ASC')
                              ->get();
        }

        // Pagination
        $perPage = 20;
        $page = $request->get('page', 1);

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $expenses->forPage($page, $perPage),
            $expenses->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.transactions.expense_report', [
            'expenses' => $paginated,
            'dairies' => $dairies,
            'categories' => $categories,
        ]);
    }

    // -----------------------------------------------------
    // EXPORT TO EXCEL
    // -----------------------------------------------------
    public function export(Request $request)
    {
        $filters = $request->only([
            'dairy_id',
            'expensecategory_id',
            'product_id',
            'expense_item',
            'start_date',
            'end_date'
        ]);

        return Excel::download(new ExpenseExport($filters), 'expenses.xlsx');
    }
}
