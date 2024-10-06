<?php

namespace App\Http\Requests\Bills;

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
            "firm_id" => "required",
            "deal" => "required",
            "act_user_id" => "required",
            "date" => "required",
            "orders" => "required",
            "orders.0.name" => "required| min:3",
            "orders.*.unit_price" => "nullable",
        ];
    }
}
