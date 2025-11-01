<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dairy;
use App\Models\FundAllocation;
use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get current financial year (April 1 → March 31)
        $now = Carbon::now();
        $currentFinancialYearStart = $now->month >= 4
            ? Carbon::create($now->year, 4, 1)
            : Carbon::create($now->year - 1, 4, 1);
        $currentFinancialYearEnd = $currentFinancialYearStart->copy()->addYear()->subDay();

        // Get selected financial year from request
        $selectedYear = $request->get('financial_year');
        if (!$selectedYear) {
            // Default: current financial year (e.g., 2025-2026)
            $selectedYear = $currentFinancialYearStart->format('Y') . '-' . $currentFinancialYearEnd->format('Y');
        }

        // Parse start/end for the selected financial year
        [$startYear, $endYear] = explode('-', $selectedYear);
        $financialYearStart = Carbon::create($startYear, 4, 1);
        $financialYearEnd = Carbon::create($endYear, 3, 31, 23, 59, 59);

        // Get selected dairy
        $selectedDairyId = $request->get('dairy_id');

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

        return view('admin.dashboard', compact(
            'totalAllocatedFund',
            'totalExpenses',
            'dairies',
            'selectedDairyId',
            'dairyData',
            'chartData',
            'selectedYear',
            'financialYears'
        ));
    }
}
