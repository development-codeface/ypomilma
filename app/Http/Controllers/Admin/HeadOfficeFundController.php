<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Models\HeadOfficeFund;
use Illuminate\Http\Request;
use Gate;

class HeadOfficeFundController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('headofficefund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $headOffices = HeadOfficeFund::latest()->paginate(10);
        return view('admin.headoffices.index', compact('headOffices'));
    }

    public function create()
    {
        return view('admin.headoffices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'financial_year' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        HeadOfficeFund::create([
        'financial_year' => $request->financial_year,
        'amount' => $request->amount,
        'balance_amount' => $request->amount, // INITIAL BALANCE = TOTAL AMOUNT
       ]);

       return redirect()->route('admin.headoffices.index')->with('success', 'Head Office created successfully.');
    }

    public function edit(HeadOfficeFund $headoffice)
    {
        return view('admin.headoffices.edit', compact('headoffice'));
    }

   public function update(Request $request, HeadOfficeFund $headoffice)
    {
        $request->validate([
            'financial_year' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // Get total allocated for this financial year
        $allocated = \App\Models\FundAllocation::where('financial_year', $headoffice->financial_year)
            ->sum('amount');

        // New available balance = new amount - allocated
        $newBalance = $request->amount - $allocated;

        if ($newBalance < 0) {
            return back()->withErrors([
                'amount' => "You cannot reduce amount below already allocated fund. 
                            Allocated: $allocated"
            ])->withInput();
        }

        $headoffice->update([
            'financial_year' => $request->financial_year,
            'amount' => $request->amount,
            'balance_amount' => $newBalance, // UPDATED BALANCE
        ]);

        return redirect()->route('admin.headoffices.index')
            ->with('success', 'Head Office updated successfully.');
    }


    public function destroy(HeadOfficeFund $headoffice)
    {
        $headoffice->delete();
        return redirect()->route('admin.headoffices.index')->with('success', 'Head Office deleted successfully.');
    }
}
