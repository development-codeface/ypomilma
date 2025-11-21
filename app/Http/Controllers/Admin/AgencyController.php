<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Agency;
use App\Models\Dairy;
use Gate;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('agency_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();
        $agency_data = Agency::query();
        $data['agency'] = $agency_data->where('dairy_id', $dairy_id)->orderBy('id', 'DESC')->paginate(15);
        return view('admin.agency.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('agency_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.agency.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('agency_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'name'     => 'required|string|max:255',
            'address'     => 'required',
            'contact_no'     => 'required',
            'email'     => 'required|email|unique:agencies,email',
        ]);

        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();
        $lastAgency = Agency::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $lastAgency ? (int) substr($lastAgency->id, 3) + 1 : 1;
        $agencyCode = 'AGENCY' . str_pad($num, 5, '0', STR_PAD_LEFT);
        // Store the agency data
        Agency::create([
            'agency_code' => $agencyCode,
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'contact_no' => $request->input('contact_no'),
            'email' => $request->input('email'),
            'dairy_id' => $dairy_id,
        ]);
        return redirect()->route('admin.aggency.index')->with('success', 'Agency created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('agency_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['edit_agency'] = Agency::findOrFail($id);
        return view('admin.agency.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         abort_if(Gate::denies('agency_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'name'     => 'required|string|max:255',
            'address'     => 'required',
            'contact_no'     => 'required',
            'email'     => 'required|email|unique:agencies,email,' . $id,
        ]);

        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();
        // Update the agency data
        $agency = Agency::findOrFail($id);
        $agency->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'contact_no' => $request->input('contact_no'),
            'email' => $request->input('email'),
            'dairy_id' => $dairy_id,
        ]);

        return redirect()->route('admin.aggency.index')->with('success', 'Agency updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
         abort_if(Gate::denies('agency_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delete_agency = Agency::findOrFail($id);
        $delete_agency->delete();
        return redirect()->route('admin.aggency.index')->with('success', 'Agency deleted successfully.');
    }
}
