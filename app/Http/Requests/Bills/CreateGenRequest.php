<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;

class CreateGenRequest extends FormRequest
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
           // "clients" => "required",
           // 'clients.firstname' => 'required',
           // 'clients.lastname' => 'required',
            "companies" => "nullable",
            "companies.name" => "nullable",
            "date" => "required",
            "deal" => "required",
            "firm_id"=> "required",
            "orders" => "required",
            "orders.*.amount" => "required",
            "orders.*.unit_price" => "required",
            //"orders.*.currency" => "required",
           // "locale" => "required"
        ];
    }
}
