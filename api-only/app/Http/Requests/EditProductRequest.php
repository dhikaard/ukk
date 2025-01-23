<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'productId' => 'required|integer|exists:products,product_id',
            'productName' => 'required|string|max:255',
            'brandId' => 'required|integer',
            'brandName' => 'nullable|string|max:255',
            'ctgrId' => 'required|integer',
            'ctgrName' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'fineBill' => 'required|numeric|min:0|max:100',
            'desc' => 'nullable|string',
            'urlImg' => 'nullable|image|max:2048',
            'active' => 'required|string|in:Y,N',
        ];
    }
}
