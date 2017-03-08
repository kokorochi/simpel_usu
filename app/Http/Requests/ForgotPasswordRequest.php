<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'input.nidn' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'input.nidn.required' => 'NIDN harus diisi'
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

        $user = User::where('nidn', $this->input['nidn'])->first();
        if(is_null($user))
        {
            array_push($ret, 'NIDN tidak ditemukan pada sistem penelitian, hubungi LP / PSI untuk registrasi NIDN');
        }else{
            $lecturer = $user->lecturer()->first();
            if(!filter_var($lecturer->email, FILTER_VALIDATE_EMAIL))
            {
                array_push($ret, 'Email di SIMSDM belum diisi / tidak valid');
            }
        }

        return $ret;
    }
}
