<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'productName' => 'required|string|max:256',
            'brandId' => 'required|integer',
            'brandName' => 'nullable|string|max:256',
            'ctgrId' => 'required|integer',
            'ctgrName' => 'nullable|string|max:256',
            'desc' => 'nullable|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'fineBill' => 'required|numeric',
            'urlImg' => 'nullable',
        ];
    }
}
