<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOutputTypeRequest extends FormRequest {
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
            'input.output_code' => 'required|unique:output_types,output_code|max:3|string',
            'input.output_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'input.output_code.required' => 'Kode luaran harus diisi',
            'input.output_code.unique'   => 'Kode luaran sudah pernah dipakai di luaran yang lain',
            'input.output_code.max'      => 'Kode luaran maksimal adalah 3 karakter',
            'input.output_code.string'   => 'Kode luaran harus string',
            'input.output_name.required' => 'Nama luaran harus diisi',
        ];
    }
}
