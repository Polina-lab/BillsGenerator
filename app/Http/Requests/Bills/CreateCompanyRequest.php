<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
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
            "name" => "required",
            "phone" => "nullable",
            "reg_num" => "required",
            "comment"=> "nullable",
            "email" => "nullable",
            "lepping" => "boolean|required",
            "status"=> "boolean|required",
            "extra" => "nullable",
            "location" => "nullable",
            "client_id" => "nullable",
            "user_id" => "nullable"
        ];
    }
}
