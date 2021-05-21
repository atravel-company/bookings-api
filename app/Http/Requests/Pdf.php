<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Pdf extends FormRequest
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
            'path_image' => 'required|mimes:doc,docx,pdf|max:10000',
            'title' => 'required',
        ];
    }

    public function message(){

        return [
            'path_image.required' => "Anexo obrigatorio",
            'path_image.mimes' => "Anexo deve ser nos formatos .doc, .docx, .pdf",
            'path_image.max' => "Anexo deve ter tamanho maximo de 10Mb",
        ];
    }
}
