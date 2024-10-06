<?php

namespace App\Http\Requests\Clients;

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
            "client" => "required",
            "client.firstname" => "required",
            "client.lastname" => "required",
            "client.companies" => "nullable",
            "client.companies.*.name" => "required_if:client.companies,!=,null",
            "client.companies.*.user_id" => "required_if:client.companies,!=,null",
            "client.location" => "nullable",
        ];
    }
}
