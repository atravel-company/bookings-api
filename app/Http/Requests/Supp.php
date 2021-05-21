<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Supp extends FormRequest
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
            //
        'path_image' => 'required',
        'name' => 'required|max:254',
        'social_denomination' => 'required|max:254',
        'fiscal_number' => 'max:254',
        'web' => 'max:254'
        ];
    }
}
