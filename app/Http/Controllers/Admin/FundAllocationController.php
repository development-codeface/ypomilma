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

        $financialYears = FundAllocation::select('financial_year')
            ->distinct()
            ->orderBy('financial_year', 'desc')
            ->pluck('financial_year');

        $dairies = Dairy::orderBy('name')->get();

        $query = FundAllocation::with('dairy');

        if ($request->filled('financial_year')) {
            $query->where('financial_year', $request->financial_year);
        }

        if ($request->filled('dairy_id')) {
            $query->where('dairy_id', $request->dairy_id);
        }

        $allocations = $query->orderBy('allocation_date', 'desc')->paginate(10);

        return view('admin.fund_allocations.index', compact('allocations', 'financialYears', 'dairies'));
    }

    public function create()
    {
        abort_if(Gate::denies('fundallocation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $dairies = Dairy::all();
        return view('admin.fund_allocations.create', compact('dairies'));
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

        $headOffice = HeadOfficeFund::where('financial_year', $request->financial_year)->first();

        if (!$headOffice) {
            return back()->withErrors([
                'financial_year' => 'No Head Office account found for the selected financial year.',
            ])->withInput();
        }

        // Available HO balance BEFORE this allocation
        $availableBalance = $headOffice->balance_amount;

        if ($request->amount > $availableBalance) {
            return back()->withErrors([
                'amount' => "Insufficient funds in Head Office account. Available: " . number_format($availableBalance, 2),
            ])->withInput();
        }

        DB::transaction(function () use ($request, $headOffice) {

            // 1️⃣ Create Fund Allocation
            $fund = FundAllocation::create([
                'dairy_id' => $request->dairy_id,
                'amount' => $request->amount,
                'allocation_date' => $request->allocation_date,
                'financial_year' => $request->financial_year,
                'remarks' => $request->remarks,
                'status' => 'approved',
            ]);

            // 2️⃣ Transaction Entry
            Transactions::create([
                'dairy_id' => $request->dairy_id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'Fund allocation received',
                'reference_id' => $fund->id,
                'transaction_date' => $request->allocation_date,
            ]);

            // 3️⃣ Update Dairy Account
            $account = Account::firstOrNew(['dairy_id' => $request->dairy_id]);
            $account->main_balance = ($account->exists)
                ? $account->main_balance + $request->amount
                : $request->amount;
            $account->save();

            // 4️⃣ Deduct from Head Office balance_amount
            $headOffice->balance_amount -= $request->amount;
            $headOffice->save();
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

    public function edit($id)
    {
        abort_if(Gate::denies('fundallocation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allocation = FundAllocation::findOrFail($id);
        $dairies = Dairy::all();

        return view('admin.fund_allocations.edit', compact('allocation', 'dairies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dairy_id' => 'required|exists:dairies,id',
            'amount' => 'required|numeric|min:0.01',
            'allocation_date' => 'required|date',
            'financial_year' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $allocation = FundAllocation::findOrFail($id);
        $oldAmount = $allocation->amount;
        $newAmount = $request->amount;

        $headOffice = HeadOfficeFund::where('financial_year', $request->financial_year)->first();

        if (!$headOffice) {
            return back()->withErrors([
                'financial_year' => 'No Head Office account found.',
            ])->withInput();
        }

        $availableHO = $headOffice->balance_amount;
        $account = Account::firstOrNew(['dairy_id' => $request->dairy_id]);
        $currentBalance = $account->exists ? $account->main_balance : 0;

        // CASE 1 — Increase allocation
        if ($newAmount > $oldAmount) {
            $extra = $newAmount - $oldAmount;

            if ($extra > $availableHO) {
                return back()->withErrors([
                    'amount' => "Insufficient HO balance. Available: " . number_format($availableHO, 2)
                ])->withInput();
            }
        }

        // CASE 2 — Reduce allocation
        if ($newAmount < $oldAmount) {
            $reduce = $oldAmount - $newAmount;

            if ($reduce > $currentBalance) {
                return back()->withErrors([
                    'amount' => "Dairy does not have enough balance. Current: " . number_format($currentBalance, 2)
                ])->withInput();
            }
        }

        DB::transaction(function () use ($allocation, $request, $oldAmount, $newAmount, $account, $headOffice) {

            $difference = $newAmount - $oldAmount;

            // 1️⃣ Update allocation
            $allocation->update([
                'dairy_id' => $request->dairy_id,
                'amount' => $newAmount,
                'allocation_date' => $request->allocation_date,
                'financial_year' => $request->financial_year,
                'remarks' => $request->remarks,
            ]);

            // 2️⃣ Update Dairy balance
            $account->main_balance += $difference;
            $account->save();

            // 3️⃣ Update Head Office balance
            if ($difference > 0) {
                $headOffice->balance_amount -= $difference;   // extra taken
            } else {
                $headOffice->balance_amount += abs($difference); // refund
            }
            $headOffice->save();

            // 4️⃣ Update Transaction
            $transaction = Transactions::where('reference_id', $allocation->id)
                ->where('type', 'credit')
                ->first();

            if ($transaction) {
                $transaction->update([
                    'amount' => $newAmount,
                    'transaction_date' => $request->allocation_date,
                    'description' => 'Fund allocation updated',
                ]);
            }
        });

        return redirect()->route('admin.fund_allocations.index')
            ->with('success', 'Fund allocation updated successfully.');
    }

    public function adjust($id)
    {
        abort_if(Gate::denies('fundallocation_adjust'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocation = FundAllocation::with('dairy')->findOrFail($id);

        return view('admin.fund_allocations.adjust', compact('allocation'));
    }

    public function updateAdjust(Request $request, $id)
    {
        $request->validate([
            'adjust_type' => 'required|in:add,reduce',
            'amount' => 'required|numeric|min:0.01',
            'remarks' => 'nullable|string'
        ]);

        $allocation = FundAllocation::findOrFail($id);
        $adjustAmount = $request->amount;
        $type = $request->adjust_type;

        $headOffice = HeadOfficeFund::where('financial_year', $allocation->financial_year)->first();
        $availableHO = $headOffice->balance_amount;

        $account = Account::firstOrNew(['dairy_id' => $allocation->dairy_id]);
        $dairyBalance = $account->exists ? $account->main_balance : 0;

        DB::transaction(function () use (
            $type, $adjustAmount, $allocation, $account, $headOffice
        ) {

            if ($type === 'add') {

                if ($adjustAmount > $headOffice->balance_amount) {
                    abort(400, "Insufficient Head Office balance.");
                }

                $allocation->amount += $adjustAmount;
                $account->main_balance += $adjustAmount;

                // HO deduct
                $headOffice->balance_amount -= $adjustAmount;

                Transactions::create([
                    'dairy_id' => $allocation->dairy_id,
                    'type' => 'credit',
                    'amount' => $adjustAmount,
                    'reference_id' => $allocation->id,
                    'description' => 'Fund increased',
                    'transaction_date' => now(),
                ]);
            }

            if ($type === 'reduce') {

                if ($adjustAmount > $account->main_balance) {
                    abort(400, "Dairy doesn't have enough balance.");
                }

                if ($adjustAmount > $allocation->amount) {
                    abort(400, "Cannot reduce more than allocated amount.");
                }

                $allocation->amount -= $adjustAmount;
                $account->main_balance -= $adjustAmount;

                // HO refund
                $headOffice->balance_amount += $adjustAmount;

                Transactions::create([
                    'dairy_id' => $allocation->dairy_id,
                    'type' => 'debit',
                    'amount' => $adjustAmount,
                    'reference_id' => $allocation->id,
                    'description' => 'Fund reduced',
                    'transaction_date' => now(),
                ]);
            }

            $allocation->save();
            $account->save();
            $headOffice->save();
        });

        return redirect()->route('admin.fund_allocations.index')
            ->with('success', 'Fund updated successfully.');
    }
}
