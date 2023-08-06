<?php

namespace App\Http\Requests;

use App\Enums\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:55',
            'short_description' => 'nullable|string|min:3,max:115',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'status' => 'nullable|string|in:' . implode(',', ProductStatusEnum::values()),
            'tags' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'discount' => 'nullable|numeric',
            'upc' => 'nullable|string|max:13',
            'delivery_fee' => 'nullable|numeric',
            'options' => 'nullable|array',
        ];

        if ($this->isMethod('post')) {
            $rules['slug'] .= '|unique:products,slug';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['slug'] .= '|unique:products,slug,' . $this->route('product')->id;
        }

        return $rules;
    }
}
