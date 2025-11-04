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

            // Parse start/end for the selected financial year
            [$startYear, $endYear] = explode('-', $selectedYear);
            $financialYearStart = Carbon::create($startYear, 4, 1);
            $financialYearEnd = Carbon::create($endYear, 3, 31, 23, 59, 59);

            $selectedDairyId = $request->get('dairy_id');
            // dd($selectedDairyId);

            $totalAllocatedFund = FundAllocation::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                ->sum('amount');

             $totalExpenses = Transactions::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
             ->whereIn('type', ['debit', 'hold']) 
             ->sum('amount');

            $dairies = Dairy::select('id', 'name')->get();

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

            $chartData = Dairy::select('name', 'id')
                ->with(['fundAllocations' => function ($q) use ($financialYearStart, $financialYearEnd) {
                    $q->select('dairy_id', DB::raw('SUM(amount) as total_fund'))
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->groupBy('dairy_id');
                }])
                ->with(['expenses' => function ($q) use ($financialYearStart, $financialYearEnd) {
                    $q->select('dairy_id', DB::raw('SUM(amount) as total_expense'))
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->groupBy('dairy_id');
                }])
                ->get();

            $financialYears = collect(range($now->year + 1, $now->year - 4))
                ->map(function ($year) {
                    return ($year - 1) . '-' . $year;
                });
        } else {
             $currentFinancialYearStart = $now->month >= 4
                ? Carbon::create($now->year, 4, 1)
                : Carbon::create($now->year - 1, 4, 1);
            $currentFinancialYearEnd = $currentFinancialYearStart->copy()->addYear()->subDay();

            // Get selected financial year from request
            // $selectedYear = $request->get('financial_year');

            $currentYear = Carbon::now()->year;       // 2025
            $previousYear = Carbon::now()->subYear()->year;  // 2024

            $selectedYear = $previousYear . '-' . $currentYear;

            if (!$selectedYear) {
                // Default: current financial year (e.g., 2025-2026)
                $selectedYear = $currentFinancialYearStart->format('Y') . '-' . $currentFinancialYearEnd->format('Y');
            }

            // Parse start/end for the selected financial year
            [$startYear, $endYear] = explode('-', $selectedYear);
            $financialYearStart = Carbon::create($startYear, 4, 1);
            $financialYearEnd = Carbon::create($endYear, 3, 31, 23, 59, 59);

            // Get selected dairy
            // $selectedDairyId = $request->get('dairy_id');
            $user_id = auth()->user()->id;
            $selectedDairyId = Dairy::where('admin_userid', $user_id)->value('id');

            // Total allocated funds in selected financial year
            $totalAllocatedFund = FundAllocation::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                ->sum('amount');

            // Total expenses in selected financial year
            $totalExpenses = Expense::whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                ->sum('amount');

            // Dairy list
            $dairies = Dairy::select('id', 'name')->get();

            // Dairy-specific details (if selected)
            $dairyData = null;
            if ($selectedDairyId) {
                $dairyData = [
                    'fund_allocated' => FundAllocation::where('dairy_id', $selectedDairyId)
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->sum('amount'),
                    'expenses' => Expense::where('dairy_id', $selectedDairyId)
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->sum('amount'),
                    'invoices' => Invoice::where('dairy_id', $selectedDairyId)
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->count(),
                ];
            }

            // Dairy-wise chart data (for visualization)
            $chartData = Dairy::select('name', 'id')
                ->with(['fundAllocations' => function ($q) use ($financialYearStart, $financialYearEnd) {
                    $q->select('dairy_id', DB::raw('SUM(amount) as total_fund'))
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->groupBy('dairy_id');
                }])
                ->with(['expenses' => function ($q) use ($financialYearStart, $financialYearEnd) {
                    $q->select('dairy_id', DB::raw('SUM(amount) as total_expense'))
                        ->whereBetween('created_at', [$financialYearStart, $financialYearEnd])
                        ->groupBy('dairy_id');
                }])
                ->get();

            // Available financial years for dropdown (last 5 years)
            $financialYears = collect(range($now->year + 1, $now->year - 4))
                ->map(function ($year) {
                    return ($year - 1) . '-' . $year;
                });
        }

        return view('admin.dashboard', compact(
            'totalAllocatedFund',
            'totalExpenses',
            'dairies',
            'selectedDairyId',
            'dairyData',
            'chartData',
            'selectedYear',
            'financialYears',
            'role'
        ));
    }
}
