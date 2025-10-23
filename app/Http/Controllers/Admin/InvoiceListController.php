<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceListController extends Controller
{
    /**
     * function for view page invoice list.
     *
     * @return void
     */
    public function index()
    {
        $invoice_list = Invoice::with(['dairy', 'vendor']);
        $user_id = auth()->user()->id;
        $invoice_list->whereHas('dairy', function ($query) use ($user_id) {
            $query->where('admin_userid', $user_id);
        });
        $data['invoice_list'] = $invoice_list->orderBy('id', 'DESC')->paginate(15);
// dd($data['invoice_list']);
        return view('admin.invoice_list.index',$data);
    }
}
