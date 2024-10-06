<?php

namespace App\Http\Requests\Firm;

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
            "name"=> "required|min:1|max:100",
            "reg_num"=> "required",
            "status"=> "nullable",
            "phone"=>"nullable" ,
            "address"=> "nullable|max:200",
        ];
    }
}
