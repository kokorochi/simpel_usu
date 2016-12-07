<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
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
            'nidn' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nidn.required' => 'NIDN harus diisi',
            'password.required' => 'Password harus diisi',
        ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator)
        {
            $this->after($validator);
        });
    }


    public function after($validator)
    {
        $check = $this->checkBeforeSave();
        if (count($check) > 0)
        {
            foreach ($check as $item)
            {
                $validator->errors()->add('sumErrors', $item);
            }
        }
    }

    private function checkBeforeSave()
    {
        $ret = [];
        if($this->nidn === '' || $this->password === '') return $ret;
        if(! (Auth::attempt(['nidn' => $this->nidn, 'password' => $this->password])))
        {
            array_push($ret, 'NIDN / Password yang dimasukkan salah');
        }
        return $ret;
    }
}
