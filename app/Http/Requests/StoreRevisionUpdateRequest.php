<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRevisionUpdateRequest extends FormRequest
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
            'file_propose_final' => 'required|mimes:pdf|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'file_propose_final.required' => 'Usulan perbaikan harus diunggah',
            'file_propose_final.mimes'    => 'Usulan perbaikan harus dalam bentuk PDF',
            'file_propose_final.max'      => 'Maksimal file usulan perbaikan adalah 5MB'
        ];
    }
}
