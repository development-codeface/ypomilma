<?php

namespace App\Http\Requests;
use Gate;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;


class UpdateVendorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('vendor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true; // or check for permissions
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $this->vendor->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ];
    }
}
