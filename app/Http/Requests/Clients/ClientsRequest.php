<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class ClientsRequest extends FormRequest
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
            "firstname" => "nullable",
            "lastname" => "nullable",
            "phone" => "nullable",
            "email" => "nullable",
            "id_code" => "nullable",
            "name" => "nullable|min:2"
        ];
    }
}
