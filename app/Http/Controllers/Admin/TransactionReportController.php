<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Dairy;

class TransactionReportController extends Controller
{
  public function index(Request $request)
{
    $user = auth()->user();
    $roleName = strtolower($user->role_name);

    $transactions = collect(); 
    $dairies = Dairy::orderBy('name')->get();

    // Base query setup
    $query = Transactions::query();

    // Role-based restrictions
    if ($roleName === 'superadmin') {
        // ✅ Superadmin - original logic remains unchanged
        if ($request->filled('dairy_id')) {
            $request->validate([
                'dairy_id' => 'required|exists:dairies,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ], [
                'dairy_id.required' => 'Please select a dairy.',
                'dairy_id.exists' => 'Selected dairy does not exist.',
            ]);

            $query->where('dairy_id', $request->dairy_id);
        }
    } else {
        // 👥 Non-superadmin - only their own dairy transactions
        $dairy = Dairy::where('admin_userid', $user->id)->first();
        if ($dairy) {
            $query->where('dairy_id', $dairy->id);
        } else {
            // no dairy linked to user — show nothing
            $query->whereNull('dairy_id');
        }
    }

    // Additional filters (common for all roles)
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('reference_no')) {
        $query->where('reference_no', 'like', "%{$request->reference_no}%");
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
    }

    // Execute query only if dairy restriction applied (superadmin with dairy_id OR user with linked dairy)
    if ($query->getQuery()->wheres) {
        $transactions = $query->with('dairy')->orderBy('transaction_date', 'asc')->get();

        // Running balance calculation
        $balance = 0;
        foreach ($transactions as $txn) {
            if ($txn->type === 'credit') {
                $balance += $txn->amount;
            } elseif (in_array($txn->type, ['debit', 'refund', 'hold'])) {
                $balance -= $txn->amount;
            }
            $txn->running_balance = $balance;
        }
    }

    // Manual pagination
    $perPage = 20;
    $page = $request->get('page', 1);
    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $transactions->forPage($page, $perPage),
        $transactions->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('admin.transactions.index', [
        'transactions' => $paginated,
        'dairies' => $dairies,
    ]);
}




    public function export(Request $request)
    {
        $filters = $request->only(['dairy_id', 'type', 'status', 'reference_no', 'start_date', 'end_date']);
        return Excel::download(new TransactionsExport($filters), 'transactions.xlsx');
    }
}
