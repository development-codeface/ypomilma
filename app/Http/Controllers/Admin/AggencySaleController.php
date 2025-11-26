<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AggencySale;
use App\Models\AggencyBill;
use App\Models\Dairy;
use App\Models\Agency;
use Gate;

class AggencySaleController extends Controller
{
    /**
     * function for get agency sale list.
     *
     * @return void
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('agency_sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();
        // $data['agency_name'] = AggencySale::select('name')->distinct()->get();
        $data['agency_name'] = Agency::where('dairy_id', $dairy_id)->select('name','id')->distinct()->get();
        $agency_sale = AggencySale::with('agency');

        if (request()->has('name') && !empty(request()->name)) {
            $agency_sale->where('agency_id', request()->name);
        }

        if ($request->from_date && $request->to_date) {
            $agency_sale->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);
        } elseif ($request->from_date) {
            $agency_sale->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $agency_sale->whereDate('created_at', '<=', $request->to_date);
        }

        $data['aggencySales'] = $agency_sale->where('dairy_id', $dairy_id)->orderBy('id', 'DESC')->paginate(15); // Fetch aggency sales data from the database
        return view('admin.aggency_sale.index', $data);
    }

    /**
     * function for  show  agecy sale list.
     *
     * @return void
     */
    public function show($id)
    {
        abort_if(Gate::denies('agency_sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //$data['aggencyShow'] = AggencyBill::with('product')->where('aggency_sale_id', $id)->get();
        $data['aggencyShow'] = AggencyBill::with(['product', 'units'])
        ->where('aggency_sale_id', $id)
        ->get();

        return view('admin.aggency_sale.show', $data);
    }
}
