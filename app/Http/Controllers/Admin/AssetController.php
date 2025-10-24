<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assets;

class AssetController extends Controller
{
    /**
     * function for view page invoice list.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $assets_list = Assets::with(['dairy', 'product']);

        if ($request->status) {
            $assets_list->where('status', $request->status);
        }

        if ($request->from_date && $request->to_date) {
            $assets_list->whereDate('purchase_date', '>=', $request->from_date)
                ->whereDate('purchase_date', '<=', $request->to_date);
        } elseif ($request->from_date) {
            $assets_list->whereDate('purchase_date', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $assets_list->whereDate('purchase_date', '<=', $request->to_date);
        }
        
        $user_id = auth()->user()->id;
        $assets_list->whereHas('dairy', function ($query) use ($user_id) {
            $query->where('admin_userid', $user_id);
        });

        $data['assets'] = $assets_list->orderBy('id', 'DESC')->paginate(15);
        return view('admin.asset_manage.index', $data);
    }
}
