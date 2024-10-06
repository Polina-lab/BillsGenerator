<?php

namespace App\Http\Requests\Coupons;

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
            "id" => "required",
            'code' => "required",//unique:coupons,code
            'amount_percent' => "nullable",
            'amount_eur' =>  "nullable" ,
            'start_date' => "date|required",
            'end_date' => "date|nullable",
            'usages_remaining' => "integer|nullable",
            'user_id' => "integer"
        ];
    }
}
