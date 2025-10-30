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
        $query = Transactions::query();

        // Filtering logic
        if ($request->filled('dairy_id')) {
            $query->where('dairy_id', $request->dairy_id);
        }

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

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20);

         $dairies = Dairy::orderBy('name')->get();

        return view('admin.transactions.index', compact('transactions', 'dairies'));
    }

    public function export(Request $request)
    {
        // Reuse filters from index
        $filters = $request->only(['dairy_id', 'type', 'status', 'reference_no', 'start_date', 'end_date']);
        return Excel::download(new TransactionsExport($filters), 'transactions.xlsx');
    }
}
