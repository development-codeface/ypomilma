<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assets;
use App\Models\Dairy;
use App\Traits\AssetManage;


class AssetController extends Controller
{
    protected $assetManage;

    public function __construct(AssetManage $assetManage)
    {
        $this->assetManage = $assetManage;
    }

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

    /**
     * function for view page invoice list.
     *
     * @return void
     */
    public function create(Request $request)
    {
        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id');
        $data['assets'] = Assets::with('product')->where('dairy_id', $dairy_id)->get();
        return view('admin.asset_manage.create', $data);
    }

    /**
     * function for get asset list.
     *
     * @return void
     */
    public function getAssetDetails($assetId)
    {
        // dd($assetId);
        $asset = Assets::with('invoiceItem')->where('id', $assetId)->first();
        if ($asset) {
            return response()->json([
                'success' => true,
                'data' => $asset
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found'
            ], 404);
        }
    }

    /**
     * function for get asset list.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'address'     => 'required',
            'contact_no'     => 'required',
        ]);

        $data = $request->all();
        $items = $request->items;
        foreach ($items as $index => $item) {
            $asset = Assets::find($item['asset_id']);

            if (!$asset) {
                return redirect()->back()
                    ->withErrors(["items" => "Invalid product selected."])
                    ->withInput();
            }

            if ($item['quantity'] > $asset->quantity) {
                return redirect()->back()
                    ->withErrors([
                        "quantity" => "Requested quantity ({$item['quantity']}) exceeds available stock ({$asset->quantity})."
                    ])
                    ->withInput();
            }
        }

        $this->assetManage->AssetStore($data, $items);
        return redirect()->route('admin.asset-management.index')->with('success', 'Agency Invoice created successfully.');
    }
}
