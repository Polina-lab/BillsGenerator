<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'address' => "min:3 | max:150",
            'email' => 'email',
            'firm' => 'nullable',
            'bank' => 'nullable',
            'firstname' => 'required|min:2|max:100',
            'lastname' => "required|min:2|max:100 ",
            'langs_id' => 	"required",
            'phone'	=> "required",
            'timezone' => 'nullable',
            'avatar' => "nullable"
        ];
    }
}
