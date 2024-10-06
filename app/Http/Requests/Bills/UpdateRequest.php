<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "id" => "integer",
            "number" =>"required",
            "firm_id" => "required",
            "user_id" => "integer",
            //"price" => "required",
            'bank_id' => 'nullable',
            "status" => "required",
            "vat_id" => "nullable",
            "date" => "required",
            "act_user_id" => "integer|nullable",
            "orders" => "required",
            "orders.*.amount" => "required",
            'orders.*.unit_price' => "required|numeric|between:0,10000000"
        ];
    }
}
