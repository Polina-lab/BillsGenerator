<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentPlan extends FormRequest
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
            "payment_plan_id" => "required",
        //    "send_pdf" => 'boolean|required',
            "send_to" => 'string|nullable', // 'firm' or 'client'
      //      "firm_data" => "nullable",
      //      "personal_data" => "nullable"
        ];
    }
}




