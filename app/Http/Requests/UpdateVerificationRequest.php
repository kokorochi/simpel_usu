<?php

namespace App\Http\Requests;

use App\Period;
use App\Propose;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVerificationRequest extends FormRequest {
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
            foreach ($check as $key => $item)
            {
                if($key !== 'sumErrors')
                    $validator->errors()->add('member_areas_of_expertise.' . $key, $item);
                else
                    $validator->errors()->add('sumErrors', $item);
            }
        }
    }

    public function checkBeforeSave()
    {
        $ret = [];

        //Check if not waiting, then no need to update data anymore
        $propose = Propose::find($this->id);
        if(!is_null($propose))
        {
            $member = $propose->member()->where('nidn', Auth::user()->nidn)->first();
            if(is_null($member))
            {
                $ret['sumErrors'] = 'Anggota tidak ditemukan untuk NIDN = ' . Auth::user()->nidn;
            }else
            {
                if($member->status !== 'waiting')
                {
                    $ret['sumErrors'] = 'Anggota sudah memverifikasi sebelumnya, tidak dapat mengulangi proses verifikasi';
                }
            }
        }

        foreach ($this->input('member_areas_of_expertise') as $item)
        {
            if ($item === null || $item === '')
            {
                foreach ($this->input('member_nidn') as $key => $item_2)
                {
                    if ($item_2 === Auth::user()->nidn)
                    {
                        $ret[$key] = 'Bidang Kealihan harus diisi';
                    }
                }
            }
        }

//        if ($this->reject === null) // Check if user hit accept
//        {
//            $propose = Propose::find($this->id);
//            if ($propose->is_own === null)
//            {
//                $i_as_member = 0;
//                $i_as_head = 0;
//                $year = date('Y', strtotime(Carbon::now()->toDateString()));
//                $periods = Period::where('years', $year)->get();
//                foreach ($periods as $period)
//                {
//                    $propose = $period->propose()->where('created_by', Auth::user()->nidn)->where('is_own', null)->get();
//                    foreach ($propose as $item)
//                    {
//                        $flow_status = $item->flowStatus()->where('status_code', '<>', 'UT')->where('status_code', '<>', 'SS')->first();
//                        if ($flow_status !== null)
//                        {
//                            $i_as_head++;
//                        }
//                    }
//
//                    $i_as_member = 0;
//                    $proposes = $period->propose()->where('created_by', '<>', Auth::user()->nidn)->where('is_own', null)->get();
//                    foreach ($proposes as $propose)
//                    {
//                        $flow_status = $propose->flowStatus()->where('status_code', '<>', 'UT')->where('status_code', '<>', 'SS')->first();
//                        if ($flow_status !== null)
//                        {
//                            $member = $propose->member()->where('nidn', Auth::user()->nidn)->where('status', 'accepted')->first();
//                            if ($member !== null)
//                            {
//                                $i_as_member++;
//                                if ($i_as_member + $i_as_head >= 2)
//                                {
////                                array_push($ret, '1 Dosen hanya bisa menjadi ( ketua penlitian sebanyak 1 kali dan menjadi anggota sebanyak 2 kali ) atau ( anggota sebanyak 3 kali ) dalam 1 tahun');
//                                    $err_str = 'Batas maksimal untuk mengajukan usulan penelitian sudah mencapai batas! (' . $i_as_member . ' kali anggota';
//                                    if ($i_as_head > 0)
//                                    {
//                                        $err_str = $err_str . '& ' . $i_as_head . ' kali ketua)';
//                                    } else
//                                    {
//                                        $err_str = $err_str . ')';
//                                    }
//                                    array_push($ret, $err_str);
//                                    break 2;
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }

        return $ret;
    }
}
