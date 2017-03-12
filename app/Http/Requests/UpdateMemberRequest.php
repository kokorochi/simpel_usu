<?php

namespace App\Http\Requests;

use App\Propose;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest {
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
            'member_nidn.*' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'member_nidn.*.required' => 'Anggota harus diisi'
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

        $propose = Propose::find($this->id);
        if ($propose === null)
        {
            array_push($ret, 'Usulan tidak valid!');
        }

        $propose_members = $propose->member()->withTrashed()->get();

        foreach ($this->member_nidn as $item)
        {
            $exists = $propose_members->filter(function ($filter) use ($item)
            {
                return $filter->nidn == $item;
            })->first();
            if (! is_null($exists))
            {
                array_push($ret, 'Anggota dengan NIDN : ' . $item . ' sudah pernah digunakan sebelumnya!');
            }
        }

        return $ret;
    }
}
