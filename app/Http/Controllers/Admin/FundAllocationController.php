<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundAllocation;
use App\Models\Dairy;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

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

    public function store(Request $request)
    {
        $request->validate([
            'dairy_id' => 'required|exists:dairies,id',
            'amount' => 'required|numeric',
            'allocation_date' => 'required|date',
            'financial_year' => 'required|string',
           // 'status' => 'required|in:approved,pending,rejected',
        ]);

            FundAllocation::create([
            'dairy_id' => $request->dairy_id,
            'amount' => $request->amount,
            'allocation_date' => $request->allocation_date,
            'financial_year' => $request->financial_year,
            'remarks' => $request->remarks,
            'status' => 'approved', 
        ]);

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
