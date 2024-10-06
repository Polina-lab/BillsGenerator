<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\Types\Nullable;

class BillsRequest extends FormRequest
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
            "firm" => "integer|nullable",
            "user" => "integer|nullable",
            "id" => "integer|nullable",
            "number" => "integer|nullable",
            "invoice" => "integer|nullable",
            "name" => "nullable",
            "price" => "integer|nullable",
            "status" => "integer|nullable",
            "month" =>"integer|between:1,12|nullable",
            "deal" => "integer|nullable",
            "year" => "integer|nullable",
            "date" => "nullable"
        ];
    }
}
