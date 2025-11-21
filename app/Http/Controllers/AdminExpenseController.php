<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Dairy;
use Illuminate\Support\Facades\DB;

class AdminExpenseController extends Controller
{
 public function summary(Request $request)
{
    // -----------------------------
    // 1. Detect current financial year
    // -----------------------------
    $currentMonth = date('m');
    if ($currentMonth >= 4) {
        // FY starts this year
        $fy_start = date('Y') . '-04-01';
        $fy_end   = (date('Y') + 1) . '-03-31';
        $current_fy = date('Y') . '-' . (date('Y') + 1);
    } else {
        // FY started last year
        $fy_start = (date('Y') - 1) . '-04-01';
        $fy_end   = date('Y') . '-03-31';
        $current_fy = (date('Y') - 1) . '-' . date('Y');
    }

    // -----------------------------
    // 2. User-selected FY (optional)
    // -----------------------------
    if ($request->filled('financial_year')) {
        list($startYear, $endYear) = explode('-', $request->financial_year);
        $fy_start = $startYear . '-04-01';
        $fy_end   = $endYear . '-03-31';
        $current_fy = $request->financial_year;
    }

    // -----------------------------
    // 3. Load dairies and categories
    // -----------------------------
    $dairies = Dairy::orderBy('name')->get();
    $categories = DB::table('expense_categories')->orderBy('name')->get();

    $categoryData = [];

    // -----------------------------
    // 4. Loop & filter by FY dates
    // -----------------------------
    foreach ($categories as $category) {

        $dairyAmounts = [];

        foreach ($dairies as $dairy) {
            $amount = Expense::where('expensecategory_id', $category->id)
                ->where('dairy_id', $dairy->id)
                ->whereBetween('created_at', [$fy_start, $fy_end])
                ->sum('amount');

            $dairyAmounts[$dairy->name] = $amount;
        }

        $categoryData[] = [
            'category' => $category->name,
            'dairies'  => $dairyAmounts,
            'total'    => array_sum($dairyAmounts)
        ];
    }

    // -----------------------------
    // 5. Send available FY list to view
    // -----------------------------
    $financialYears = [];
    for ($y = 2020; $y <= date('Y') + 1; $y++) {
        $financialYears[] = $y . '-' . ($y + 1);
    }

    return view('admin.expenses_summary', compact(
        'categoryData',
        'dairies',
        'financialYears',
        'current_fy'
    ));
}

}
