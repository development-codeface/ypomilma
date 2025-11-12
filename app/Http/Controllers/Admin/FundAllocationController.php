<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundAllocation;
use App\Models\Dairy;
use App\Models\Transactions;
use App\Models\HeadOfficeFund;
use App\Models\Account;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class FundAllocationController extends Controller
{
  public function index(Request $request)
{
    abort_if(Gate::denies('fundallocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    // Fetch dropdown options
    $financialYears = FundAllocation::select('financial_year')
        ->distinct()
        ->orderBy('financial_year', 'desc')
        ->pluck('financial_year');

    $dairies = \App\Models\Dairy::orderBy('name')->get();

    // Build query
    $query = FundAllocation::with('dairy');

    // Apply filters
    if ($request->filled('financial_year')) {
        $query->where('financial_year', $request->financial_year);
    }

    if ($request->filled('dairy_id')) {
        $query->where('dairy_id', $request->dairy_id);
    }

    // Paginate
    $allocations = $query->orderBy('allocation_date', 'desc')->paginate(10);

    return view('admin.fund_allocations.index', compact('allocations', 'financialYears', 'dairies'));
}


    public function create()
    {
        abort_if(Gate::denies('fundallocation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $dairies = Dairy::all();
        return view('admin.fund_allocations.create', compact('dairies'));
    }

    public function storeorg(Request $request)
    {
        $request->validate([
            'dairy_id' => 'required|exists:dairies,id',
            'amount' => 'required|numeric',
            'allocation_date' => 'required|date',
            'financial_year' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1️⃣ Create Fund Allocation
            $fund = FundAllocation::create([
                'dairy_id' => $request->dairy_id,
                'amount' => $request->amount,
                'allocation_date' => $request->allocation_date,
                'financial_year' => $request->financial_year,
                'remarks' => $request->remarks,
                'status' => 'approved',
            ]);

            // 2️⃣ Create Transaction Entry
            Transactions::create([
                'dairy_id' => $request->dairy_id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'Fund allocation received',
                'reference_id' => $fund->id,
                'transaction_date' => $request->allocation_date,
            ]);

            // 3️⃣ Update or Create Account Record
            $account = Account::firstOrNew([
                'dairy_id' => $request->dairy_id,
            ]);

            if ($account->exists) {
                $account->main_balance += $request->amount;
            } else {
                $account->main_balance = $request->amount;
            }

            $account->save();
        });

        return redirect()->route('admin.fund_allocations.index')
            ->with('success', 'Fund allocation created successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dairy_id' => 'required|exists:dairies,id',
            'amount' => 'required|numeric|min:0.01',
            'allocation_date' => 'required|date',
            'financial_year' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // 🔹 Fetch Head Office balance for the selected year
        $headOffice = HeadOfficeFund::where('financial_year', $request->financial_year)->first();

        if (!$headOffice) {
            return back()->withErrors([
                'financial_year' => 'No Head Office account found for the selected financial year.',
            ])->withInput();
        }

        // 🔹 Calculate total amount already allocated for that year
        $alreadyAllocated = FundAllocation::where('financial_year', $request->financial_year)->sum('amount');

        // 🔹 Calculate available balance
        $availableBalance = $headOffice->amount - $alreadyAllocated;

        // 🔹 Check if requested amount is within available funds
        if ($request->amount > $availableBalance) {
            return back()->withErrors([
                'amount' => "Insufficient funds in Head Office account for {$request->financial_year}. 
                            Available balance: " . number_format($availableBalance, 2),
            ])->withInput();
        }

        // ✅ Proceed if balance is sufficient
        DB::transaction(function () use ($request) {
            // 1️⃣ Create Fund Allocation
            $fund = FundAllocation::create([
                'dairy_id' => $request->dairy_id,
                'amount' => $request->amount,
                'allocation_date' => $request->allocation_date,
                'financial_year' => $request->financial_year,
                'remarks' => $request->remarks,
                'status' => 'approved',
            ]);

            // 2️⃣ Create Transaction Entry
            Transactions::create([
                'dairy_id' => $request->dairy_id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'Fund allocation received',
                'reference_id' => $fund->id,
                'transaction_date' => $request->allocation_date,
            ]);

            // 3️⃣ Update or Create Account Record
            $account = Account::firstOrNew([
                'dairy_id' => $request->dairy_id,
            ]);

            $account->main_balance = ($account->exists)
                ? $account->main_balance + $request->amount
                : $request->amount;

            $account->save();
        });

        return redirect()->route('admin.fund_allocations.index')
            ->with('success', 'Fund allocation created successfully.');
    }


    public function show($id)
    {
        abort_if(Gate::denies('fundallocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allocation = FundAllocation::with('dairy')->findOrFail($id);
        return view('admin.fund_allocations.show', compact('allocation'));
    }
}
