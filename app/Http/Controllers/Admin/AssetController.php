<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dairy;
use App\Traits\AssetManage;
use App\Models\AggencyBill;
use App\Models\AggencySale;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Assets;
use App\Models\Account;
use App\Models\Agency;
use App\Models\Transactions;
use App\Models\AggencyBillUnit;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;


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
        abort_if(Gate::denies('asset_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $data['agency_name'] = Agency::where('dairy_id', $dairy_id)->get();
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
    public function storeorg(Request $request)
    {
        $request->validate([
            'agency_name'     => 'required',
        ]);

        $data = $request->all();
        $items = $request->items;

        $savedIndices = [];
        $failedIndex = null;
        $errorMessage = '';

        foreach ($items as $indexs =>  $item_data) {
            $asset = Assets::with('product')->find($item_data['asset_id']);
            $quantity = $item_data['quantity'];

            if ($asset == null) {
                return response()->json([
                    'error' => true,
                    'message' => "Invalid product selected at index {$indexs}."
                ]);
            }

            if ($quantity > $asset->quantity) {
                return response()->json([
                    'error' => true,
                    'message' => "Requested ({$asset->product->productname}) quantity ({$item_data['quantity']}) exceeds available stock ({$asset->quantity})."
                ]);
            }
        }

        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();

        $lastInvoice = AggencySale::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $lastInvoice ? (int) substr($lastInvoice->id, 3) + 1 : 1;
        $invoiceId = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);
        // $total_amount = array_sum(array_column($items, 'total'));


        $agency_sale = AggencySale::create([
            'dairy_id' => $dairy_id,
            'invoice_id' => $invoiceId,
            'agency_id' => $data['agency_name'],
        ]);

        foreach ($items as $index => $item) {
            $asset = Assets::with('product')->find($item['asset_id']);

            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];
            $discount = $item['discount'] ?? 0;
            $gstPercent = $item['gst_percent'];
            $taxType = $item['tax_type'];
            $itemTotal = $item['total'];

            if ($quantity > $asset->quantity) {
                $failedIndex = $index;
                $errorMessage = "Requested ({$asset->product->productname}) quantity ({$item['quantity']}) exceeds available stock ({$asset->quantity}).";
                break;
            }

            $baseValue = ($unitPrice * $quantity) - $discount;
            $gstAmount = 0;
            // $itemTotal = 0;
            $taxableValue = 0;

            if ($taxType === 'inclusive') {
                $taxableValue = $baseValue / (1 + $gstPercent / 100);
                $gstAmount = $baseValue - $taxableValue;
                // $itemTotal = $baseValue;
            } else {
                $taxableValue = $baseValue;
                $gstAmount = $baseValue * ($gstPercent / 100);
                // $itemTotal = $taxableValue + $gstAmount;
            }

            $account = Account::where('dairy_id', $dairy_id)->first();
            $account->main_balance = $account->main_balance - $itemTotal;
            $account->save();
     

            $quantity_asset = Assets::where('id', $item['asset_id'])->first();
            $quantity_asset->quantity = $quantity_asset->quantity - $quantity;
            $quantity_asset->save();

            $agency_total = AggencySale::where('id', $agency_sale->id)->first();
            $agency_total->total_amount = $agency_total->total_amount + $itemTotal;
            $agency_total->save();

            AggencyBill::create([
                'aggency_sale_id' => $agency_total->id,
                'asset_id' => $item['asset_id'],
                'quantity' => $quantity,
                'price' => $unitPrice,
                'gst_percent' => $gstPercent,
                'tax_type' => $taxType,
                'gst_amount' => $gstAmount,
                'discount' => $discount,
                'total' => $itemTotal,
            ]);

                $product = $asset->product;
                $expenseCategory = $product->category_id; // Assuming each product has an expense category

                $expenseData = [
                    'expensecategory_id' => $expenseCategory,
                    'expense_item' => $product->item_code,
                    'product_id' => $product->id,
                    'rate' => $unitPrice,
                    'quantity' => $quantity,
                    'amount' => $itemTotal,
                    'dairy_id' => $dairy_id,
                    'description' => 'Agency sale expense for product ' . $product->productname,
                ];

             $expense = Expense::create($expenseData);

         // Create transaction
            Transactions::create([
              'dairy_id'             => $dairy_id,
                'fund_allocation_id'   => null,
                'expense_category_id'  => $expense->expensecategory_id,
                'expense_id'           => $expense->id,
                'type'                 => 'debit',
                'amount'               => $expense->amount,
                'reference_no'         => 'EXP-' . $expense->id,
                'description'          => 'Expense: ' . $expense->expense_item.'(Invoice)',
                'status'               => 'completed',
                'transaction_date'     => now(),
            ]);

            $savedIndices[] = $index;
        }

        if ($failedIndex !== null) {
            return response()->json([
                'error' => true,
                'message' => $errorMessage,
                'failed_index' => $failedIndex,
                'saved_indices' => $savedIndices
            ]);
        }

        // $this->assetManage->AssetStore($data, $items, $dairy_id, $asset);
        return response()->json(['success' => true, 'message' => 'Agency Invoice created successfully.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'agency_name'     => 'required',
        ]);

        $data = $request->all();
        $items = $request->items;

        $savedIndices = [];
        $failedIndex = null;
        $errorMessage = '';

        foreach ($items as $indexs =>  $item_data) {
            $asset = Assets::with('product')->find($item_data['asset_id']);
            $quantity = $item_data['quantity'];

            if ($asset == null) {
                return response()->json([
                    'error' => true,
                    'message' => "Invalid product selected at index {$indexs}."
                ]);
            }

            if ($quantity > $asset->quantity) {
                return response()->json([
                    'error' => true,
                    'message' => "Requested ({$asset->product->productname}) quantity ({$item_data['quantity']}) exceeds available stock ({$asset->quantity})."
                ]);
            }
        }

        $user_id = auth()->user()->id;
        $dairy_id = Dairy::where('admin_userid', $user_id)->pluck('id')->first();

        $lastInvoice = AggencySale::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $lastInvoice ? (int) substr($lastInvoice->id, 3) + 1 : 1;
        $invoiceId = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);
        // $total_amount = array_sum(array_column($items, 'total'));


        $agency_sale = AggencySale::create([
            'dairy_id' => $dairy_id,
            'invoice_id' => $invoiceId,
            'agency_id' => $data['agency_name'],
        ]);

        foreach ($items as $index => $item) {
            $asset = Assets::with('product')->find($item['asset_id']);

            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];
            $discount = $item['discount'] ?? 0;
            $gstPercent = $item['gst_percent'];
            $taxType = $item['tax_type'];
            $itemTotal = $item['total'];

            if ($quantity > $asset->quantity) {
                $failedIndex = $index;
                $errorMessage = "Requested ({$asset->product->productname}) quantity ({$item['quantity']}) exceeds available stock ({$asset->quantity}).";
                break;
            }

            $baseValue = ($unitPrice * $quantity) - $discount;
            $gstAmount = 0;
            // $itemTotal = 0;
            $taxableValue = 0;

            if ($taxType === 'inclusive') {
                $taxableValue = $baseValue / (1 + $gstPercent / 100);
                $gstAmount = $baseValue - $taxableValue;
                // $itemTotal = $baseValue;
            } else {
                $taxableValue = $baseValue;
                $gstAmount = $baseValue * ($gstPercent / 100);
                // $itemTotal = $taxableValue + $gstAmount;
            }

            $account = Account::where('dairy_id', $dairy_id)->first();
            $account->main_balance = $account->main_balance - $itemTotal;
            $account->save();
     

            $quantity_asset = Assets::where('id', $item['asset_id'])->first();
            $quantity_asset->quantity = $quantity_asset->quantity - $quantity;
            $quantity_asset->save();

            $agency_total = AggencySale::where('id', $agency_sale->id)->first();
            $agency_total->total_amount = $agency_total->total_amount + $itemTotal;
            $agency_total->save();

           $bill = AggencyBill::create([
                'aggency_sale_id' => $agency_total->id,
                'asset_id' => $item['asset_id'],
                'quantity' => $quantity,
                'price' => $unitPrice,
                'gst_percent' => $gstPercent,
                'tax_type' => $taxType,
                'gst_amount' => $gstAmount,
                'discount' => $discount,
                'total' => $itemTotal,
            ]);

            // SAVE UNIT DETAILS (IF PROVIDED)
            $units = $item['units'] ?? null;

            if ($units) {
                $decodedUnits = json_decode($units, true);

                foreach ($decodedUnits as $unit) {
                    AggencyBillUnit::create([
                        'aggency_bill_id' => $bill->id,
                        'serial_no'  => $unit['serial_no'] ?? null,
                        'brand'      => $unit['brand'] ?? null,
                        'model'      => $unit['model'] ?? null,
                        'warranty'   => $unit['warranty'] ?? null,
                    ]);
                }
            }


                $product = $asset->product;
                $expenseCategory = $product->category_id; // Assuming each product has an expense category

                $expenseData = [
                    'expensecategory_id' => $expenseCategory,
                    'expense_item' => $product->item_code,
                    'product_id' => $product->id,
                    'rate' => $unitPrice,
                    'quantity' => $quantity,
                    'amount' => $itemTotal,
                    'dairy_id' => $dairy_id,
                    'description' => 'Agency sale expense for product ' . $product->productname,
                ];

             $expense = Expense::create($expenseData);

         // Create transaction
            Transactions::create([
              'dairy_id'             => $dairy_id,
                'fund_allocation_id'   => null,
                'expense_category_id'  => $expense->expensecategory_id,
                'expense_id'           => $expense->id,
                'type'                 => 'debit',
                'amount'               => $expense->amount,
                'reference_no'         => 'EXP-' . $expense->id,
                'description'          => 'Expense: ' . $expense->expense_item.'(Invoice)',
                'status'               => 'completed',
                'transaction_date'     => now(),
            ]);

            $savedIndices[] = $index;
        }

        if ($failedIndex !== null) {
            return response()->json([
                'error' => true,
                'message' => $errorMessage,
                'failed_index' => $failedIndex,
                'saved_indices' => $savedIndices
            ]);
        }

        // $this->assetManage->AssetStore($data, $items, $dairy_id, $asset);
        return response()->json(['success' => true, 'message' => 'Agency Invoice created successfully.']);
    }

}
