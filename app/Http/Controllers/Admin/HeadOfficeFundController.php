<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeadOfficeFund;
use Illuminate\Http\Request;

class HeadOfficeFundController extends Controller
{
    public function index()
    {
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

        HeadOfficeFund::create($request->only('financial_year', 'amount'));

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

        $headoffice->update($request->only('financial_year', 'amount'));

        return redirect()->route('admin.headoffices.index')->with('success', 'Head Office updated successfully.');
    }

    public function destroy(HeadOfficeFund $headoffice)
    {
        $headoffice->delete();
        return redirect()->route('admin.headoffices.index')->with('success', 'Head Office deleted successfully.');
    }
}
