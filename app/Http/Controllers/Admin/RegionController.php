<?php

namespace App\Http\Controllers\Admin;

use App\Asset;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRegionRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Region;
use App\Village;
use App\Gramapanchayath;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\RegionResource;

class RegionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('region_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $regions = Region::all();

        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        abort_if(Gate::denies('region_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.regions.create');
    }

    public function get(Request $request){
        
        
        if($request->resulttype == 'region'){
            $result = Region::where('id', $request->id)->get();
            foreach ($result as $item){
                $item->taluk->count();
                $item->blockpanachayath->count();
                $item->bmcc->count();
                $item->muncipality->count();
                $item->cooperation->count();
                $item->assemblyconstituency->count();
            }
        }else if($request->resulttype =='village'){
            $result = Village::where('taluk_id', $request->id)->get();
        }else if($request->resulttype =='block'){
            $result = Gramapanchayath::where('block_id', $request->id)->get();
        } else {
            $result = array();
        }
        
        return (new RegionResource($result))
        ->response()
        ->setStatusCode(Response::HTTP_CREATED);
       
         
    }

    public function store(StoreRegionRequest $request)
    {
        $regions = Region::create($request->all());

        return redirect()->route('admin.regions.index');

    }

    public function edit(Region $region)
    {
        abort_if(Gate::denies('region_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.regions.edit', compact('region'));
    }

    public function update(UpdateRegionRequest $request, Region $region)
    {
        $region->update($request->all());

        return redirect()->route('admin.regions.index');

    }

    public function show(Region $region)
    {
        abort_if(Gate::denies('region_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.regions.show', compact('region'));
    }

    public function destroy(Region $region)
    {
        abort_if(Gate::denies('region_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $region->delete();

        return back();

    }

    public function massDestroy(MassDestroyRegionRequest $request)
    {
        region::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}
