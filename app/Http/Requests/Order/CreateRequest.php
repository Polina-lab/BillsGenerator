<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required| min:3 | max:200',
            //'amount' => 'required',
            'categories_id' => 'nullable',
            'firms_id' => 'required',
            'desc' => 'nullable',
            'unit' => 'required | max:10', // this is string
            'unit_price' => "required|numeric|between:0,10000000"
        ];
    }
}
