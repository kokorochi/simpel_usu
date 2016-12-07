<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnounceRequest extends FormRequest {
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
            'title'       => 'required|min:20',
            'description' => 'required|min:100',
            'image_name'  => 'image'
        ];
    }

    public function messages()
    {
        return [
            'title.required'       => 'Judul Pengumuman harus diisi',
            'title.min'            => 'Judul Pengumuman minimal 20 karakter',
            'description.required' => 'Deskripsi harus diisi',
            'description.min'      => 'Deskripsi minimal 100 karakter',
            'image_name.image'     => 'Gambar Pengumuman harus dalam bentuk JPG/PNG',
        ];
    }
}
