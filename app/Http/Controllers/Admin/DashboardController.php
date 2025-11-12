<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dairy;
use App\Models\FundAllocation;
use App\Models\Expense;
use App\Models\Transactions;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
   public function index(Request $request)
{
    $role = auth()->user()->roles->first();
    $now = Carbon::now();

    if ($role->title == 'SuperAdmin') {
     $currentFinancialYearStart = $now->month >= 4
            ? Carbon::create($now->year, 4, 1)
            : Carbon::create($now->year - 1, 4, 1);
        $currentFinancialYearEnd = $currentFinancialYearStart->copy()->addYear()->subDay();

        $selectedYear = $request->get('financial_year');
        if (!$selectedYear) {
            $selectedYear = $currentFinancialYearStart->format('Y') . '-' . $currentFinancialYearEnd->format('Y');
        }

        [$startYear, $endYear] = explode('-', $selectedYear);
        $financialYearStart = Carbon::create($startYear, 4, 1);
        $financialYearEnd = Carbon::create($endYear, 3, 31, 23, 59, 59);

        $selectedDairyId = $request->get('dairy_id');

        $totalAllocatedFund = FundAllocation::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->sum('amount');

        $totalExpenses = Transactions::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->whereIn('type', ['debit', 'hold'])
            ->sum('amount');

        $totalBalance = $totalAllocatedFund - $totalExpenses;

        $dairies = Dairy::select('id', 'name')->get();
         $dairyData = null;
        $dairySummaries = [];
        foreach ($dairies as $dairy) {
            $allocated = FundAllocation::where('dairy_id', $dairy->id)
                ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                ->sum('amount');

            $expenses = Transactions::where('dairy_id', $dairy->id)
                ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                ->whereIn('type', ['debit', 'hold'])
                ->sum('amount');

            $balance = $allocated - $expenses;

            $dairySummaries[] = [
                'id' => $dairy->id,
                'name' => $dairy->name,
                'allocated' => $allocated,
                'expenses' => $expenses,
                'balance' => $balance,
            ];
        }

        $chartData = collect($dairySummaries);

         $financialYears = collect(range($now->year + 1, $now->year - 4))
            ->map(fn($year) => ($year - 1) . '-' . $year);
    } 
    else {
        // 🧑‍💼 Non-SuperAdmin (Dairy Admin)
        $currentFinancialYearStart = $now->month >= 4
            ? Carbon::create($now->year, 4, 1)
            : Carbon::create($now->year - 1, 4, 1);
        $currentFinancialYearEnd = $currentFinancialYearStart->copy()->addYear()->subDay();

        if ($now->month >= 4) {
            $selectedYear = $now->year . '-' . ($now->year + 1);
        } else {
            $selectedYear = ($now->year - 1) . '-' . $now->year;
        }

        [$startYear, $endYear] = explode('-', $selectedYear);
        $financialYearStart = Carbon::create($startYear, 4, 1);
        $financialYearEnd = Carbon::create($endYear, 3, 31, 23, 59, 59);

        $user_id = auth()->user()->id;
        $selectedDairyId = Dairy::where('admin_userid', $user_id)->value('id');

        $totalAllocatedFund = FundAllocation::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->sum('amount');

        $totalExpenses = Transactions::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
            ->whereIn('type', ['debit', 'hold'])
            ->sum('amount');

        $totalBalance = $totalAllocatedFund - $totalExpenses;

        $dairies = Dairy::select('id', 'name')->get();
         $dairySummaries = [];
        $dairyData = null;
        if ($selectedDairyId) {
            $dairyData = [
                'fund_allocated' => FundAllocation::where('dairy_id', $selectedDairyId)
                    ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                    ->sum('amount'),
                'expenses' => Transactions::where('dairy_id', $selectedDairyId)
                    ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                    ->whereIn('type', ['debit', 'hold'])
                    ->sum('amount'),
                'invoices' => Invoice::where('dairy_id', $selectedDairyId)
                    ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                    ->count(),
            ];
        }

        $chartData = [];
        $financialYears = collect(range($now->year + 1, $now->year - 4))
            ->map(fn($year) => ($year - 1) . '-' . $year);
    }

    return view('admin.dashboard', compact(
        'totalAllocatedFund',
        'totalExpenses',
        'totalBalance',
        'dairies',
        'selectedDairyId',
        'dairyData',
        'dairySummaries',
        'chartData',
        'selectedYear',
        'financialYears',
        'role'
    ));
}

}
