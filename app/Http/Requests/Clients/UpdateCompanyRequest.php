<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            "client_id" => "integer|nullable",
            "comment" => "text|nullable",
            "email" => "nullable",
            "lepping" => "nullable",
            "extra" => "text| nullable",
            "link" => "url|nullable",
            "location" => "nullable",
            "name" => "required",
            "phone" => "nullable",
            "status" => "boolean|nullable",
            "user_id" => "required",
        ];
    }
}
