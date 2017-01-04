<?php

namespace App\Http\Requests;

use App\Research;
use Illuminate\Foundation\Http\FormRequest;

class StoreOutputGeneralRequest extends FormRequest {
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
//            'file_name.0' => 'required',
        ];
    }

    public function messages()
    {
        return [
//            'file_name.0.required' => 'Minimal harus upload 1 file luaran'
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
        $research = Research::find($this->id);
        if ($research !== null)
        {
            $research_output_generals = $research->researchOutputGeneral()->get();
            $ctr_output = $research_output_generals->count();

            $ctr_delete = 0;
            if ($this->input('delete_output') !== null)
            {
                foreach ($this->input('delete_output') as $key => $item)
                {
                    if ($item === '1')
                    {
                        $ctr_delete++;
                    }
                }
            }
            $ctr_output = $ctr_output - $ctr_delete;
            if ($this->file(['file_name']) === null && $ctr_output === 0)
            {
                array_push($ret, 'Minimal harus ada 1 luaran yang diunggah');
            }
        }

        return $ret;
    }
}
