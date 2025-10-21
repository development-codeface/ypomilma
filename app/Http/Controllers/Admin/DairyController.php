<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dairy;
use Illuminate\Http\Request;

class DairyController extends Controller
{
    public function index()
    {
        
        $dairies = Dairy::all();
        return view('admin.dairies.index', compact('dairies'));
    }

    public function create()
    {
        return view('admin.dairies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:255',
            'presidentname' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        Dairy::create($validated);

        return redirect()->route('admin.dairies.index');
    }

    public function edit(Dairy $dairy)
    {
        return view('admin.dairies.edit', compact('dairy'));
    }

    public function update(Request $request, Dairy $dairy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:255',
            'presidentname' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        $dairy->update($validated);

        return redirect()->route('admin.dairies.index');
    }

    public function show(Dairy $dairy)
    {
        abort_if(Gate::denies('dairy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dairy.index', compact('dairy'));
    }

    public function destroy(Dairy $dairy)
    {
        $dairy->delete();

        return redirect()->route('admin.dairies.index');
    }
}
