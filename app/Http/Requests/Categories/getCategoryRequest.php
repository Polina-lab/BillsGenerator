<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class getCategoryRequest extends FormRequest
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
            "data" => "required",
            'data.firms_id' => 'integer',
            'data.name' => "required|min:3|max:100",
            'data.parent_id'=> "nullable",
            'data.order' => 'nullable',
            'firms_id' => 'required'
        ];
    }
}
