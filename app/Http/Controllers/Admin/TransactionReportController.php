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
        $transactions = collect(); 
        $dairies = Dairy::orderBy('name')->get();

        if ($request->filled('dairy_id')) {

            $request->validate([
                'dairy_id' => 'required|exists:dairies,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ], [
                'dairy_id.required' => 'Please select a dairy.',
                'dairy_id.exists' => 'Selected dairy does not exist.',
            ]);

            $query = Transactions::query();

            $query->where('dairy_id', $request->dairy_id);

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

            $transactions = $query->with('dairy')->orderBy('transaction_date', 'asc')->get();

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
