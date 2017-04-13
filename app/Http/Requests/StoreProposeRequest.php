<?php

namespace App\Http\Requests;

use App\ModelSDM\Faculty;
use App\ModelSDM\Lecturer;
use App\Output_type;
use App\Period;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProposeRequest extends FormRequest {
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
        if ($this->submit_button === 'save')
        {
            if ($this->is_own !== '1')
            {
                $rules = [
                    //Check Detail
                    'faculty_code'       => 'required',
                    'title'              => 'required',
                    'output_type'        => 'required',
                    'total_amount'       => 'required',
                    'areas_of_expertise' => 'required',
                    'time_period'        => 'required|max:2',
                    'address'            => 'required',
                    'student_involved'   => 'required|digits_between:1,2',
                    'bank_account_no'    => 'required|numeric',
                    'bank_account_name'  => 'required|max:100',
                    //End Check Detail

                    //Check Upload
                    'file_propose'       => 'mimes:pdf|max:5120',
                    'file_propose_final' => 'mimes:pdf|max:5120'
                    //End Check Detail
                ];
            } else
            {
                $rules = [
                    //Check Own Proposes
                    'own-years'          => 'numeric',
                    'own-member'         => 'numeric',
                    //End Check Own Proposes

                    //Check Detail
                    'faculty_code'       => 'required',
                    'title'              => 'required',
                    'total_amount'       => 'required',
                    'areas_of_expertise' => 'required',
                    //End Check Detail
                ];
            }

        } else
        {
            $rules = [
                'student_involved'   => 'digits_between:1,2',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'member_display.*.required' => 'Nama Anggota tidak boleh kosong',
            'member_nidn.*.required'    => 'NIDN Anggota tidak boleh kosong',

            'faculty_code.required'           => 'Fakultas tidak boleh kosong',
            'title.required'                  => 'Judul Penelitian tidak boleh kosong',
            'output_type.required'            => 'Luaran tidak boleh kosong',
            'total_amount.required'           => 'Jumlah Dana tidak boleh kosong',
            'time_period.required'            => 'Jangka Waktu tidak boleh kosong',
            'areas_of_expertise.required'     => 'Bidang Keahlian tidak boleh kosong',
            'student_involved.required'       => 'Mahasiswa terlibat tidak boleh kosong',
            'student_involved.digits_between' => 'Mahasiswa terlibat hanya boleh diisi angka',
            'address.required'                => 'Alamat Kantor/Faks/Telepon tidak boleh kosong',
            'bank_account_name.required'      => 'Nama Pemilik Bank tidak boleh kosong',
            'bank_account_name.max'           => 'Nama Pemilik Bank maksimal 100 karakter',
            'bank_account_no.required'        => 'Nomor Rekening Bank tidak boleh kosong',
            'bank_account_no.numeric'         => 'Nomor Rekening Bank hanya boleh diisi angka',
            'bank_account_no.max'             => 'Nomor Rekening Bank maksimal 100 angka',
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
        if ($this->submit_button === 'save')
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
    }

    private function checkBeforeSave()
    {
        $ret = [];
        if (is_null($this->input('is_own')) && is_null($this->input('period_id')))
        {
            array_push($ret, 'Scheme harus dipilih');

            return $ret;
        }

        //Check Own Scheme If Checked
        if ($this->input('is_own') === '1')
        {
            if (
                $this->input('own-years') === "" ||
                $this->input('own-member') === ""
//                $this->input('own-dedication_type') === "" ||
//                $this->input('own-scheme') === "" ||
//                $this->input('own-sponsor') === "" ||
//                $this->input('own-annotation') === ""
            )
            {
                array_push($ret, 'Data mandiri masih belum lengkap');

                return $ret;
            }

//            dd(count($this->input('member_nidn')) . ' ' . $this->input('own-member'));
            if (count($this->input('member_nidn')) != $this->input('own-member'))
            {
                array_push($ret, 'Jumlah anggota tidak sesuai dengan data anggota yang diisi');
            }
        } else //Check Period ID
        {
            if (
            ! Period::where('id', $this->input('period_id'))
                ->where('propose_begda', '<=', Carbon::now()->toDateString())
                ->where('propose_endda', '>=', Carbon::now()->toDateString())->exists()
            )
            {
                array_push($ret, 'Scheme yang dipilih tidak valid / sudah tidak dalam masa pengajuan proposal');
            }
            if ($this->input('bank_account_name') === null || $this->input('bank_account_name') === '' ||
                $this->input('bank_account_no') === null || $this->input('bank_account_no') === ''
            )
            {
                array_push($ret, 'Informasi rekening bank harus diisi');
            }

            $period = Period::find($this->input('period_id'));
            if ($period !== null)
            {
                if (! (count($this->input('member_nidn')) >= $period->min_member && count($this->input('member_nidn')) <= $period->max_member))
                {
                    array_push($ret, 'Jumlah anggota tidak sesuai dengan data anggota yang diisi');
                }

                if ($this->total_amount !== '')
                {
                    $lv_total_amount = str_replace(',', '', $this->total_amount);
                    if ($lv_total_amount > $period->total_amount) array_push($ret, 'Jumlah dana melebihi batas maksimal');
                }

                if ($period->allow_external == false)
                {
                    foreach ($this->member_nidn as $key => $item)
                    {
                        if ($this['external' . $key] == '1')
                        {
                            array_push($ret, 'Anggota dari luar USU tidak diperbolehkan pada scheme ini');
                            break;
                        }
                    }
                }
            }
        }

        //Check head email
        $lecturer = Lecturer::where('employee_card_serial_number', Auth::user()->nidn)->first();
        if (! filter_var($lecturer->email, FILTER_VALIDATE_EMAIL))
        {
            array_push($ret, 'Email ketua tidak valid di SIMSDM, mohon diperbaiki terlebih dahulu');
        }

        //Check output type
        $output_type_unique_ori = [];
        $valid_output = false;
        foreach ($this->input('output_type') as $key => $item)
        {
            if ($item !== '')
            {
                $valid_output = true;
                if (! Output_type::where('id', $item)->exists())
                {
                    array_push($ret, 'Luaran yang dipilih tidak valid');
                }
                array_push($output_type_unique_ori, $item);
            }
        }
        if (! $valid_output)
        {
            array_push($ret, 'Minimal 1 luaran yang dihasilkan harus diisi');
        }
        $output_type_unique = $output_type_unique_ori;
        $output_type_unique = array_unique($output_type_unique);
        if (count($output_type_unique) !== (count($output_type_unique_ori)))
        {
            array_push($ret, 'Luaran yang dipilih tidak boleh duplikasi');
        }

        //Check Member NIDN with SIMSDM Lecturer Table
        foreach ($this->input('member_nidn') as $key => $member_nidn)
        {
            if ($this->input('external' . $key) === '1')
            {
                if ($this->input('external_name')[$key] === '' ||
                    $this->input('external_affiliation')[$key] === ''
                )
                {
                    array_push($ret, 'Data dosen dari luar kurang lengkap (Nama dan Afiliasi harus diisi)');
                }
            } else
            {
                if ($this->input('member_nidn')[$key] === '' ||
                    $this->input('member_display')[$key] === ''
                )
                {
                    array_push($ret, 'Data dosen USU harus diisi dan dipilih via autocomplete');
                }
                $lecturer = Lecturer::where('employee_card_serial_number', $member_nidn)->first();
                if ($lecturer === null)
                {
                    array_push($ret, 'Anggota yang dipilih tidak valid : ' . $this->input('member_display.' . $key));
                } else
                {
                    if ($lecturer->email === null || $lecturer->email === '')
                    {
                        array_push($ret, 'Anggota yang dipilih belum mengisi email di SIMSDM : ' . $this->input('member_display.' . $key));
                    } else
                    {
                        if (! filter_var($lecturer->email, FILTER_VALIDATE_EMAIL))
                        {
                            array_push($ret, 'Anggota yang dipilih mengisi email yang tidak valid di SIMSDM : ' . $this->input('member_display.' . $key));
                        }
                    }
                }
            }
        }

        //Check Faculty with SIMSDM Faculty Table
        if (! Faculty::where('faculty_code', $this->input('faculty_code'))->where('is_faculty', 1)->exists())
        {
            array_push($ret, 'Fakultas yang dipilih tidak valid');
        }

        //Check Member Duplicate
        $member_collection = [];
        foreach ($this->input('member_nidn') as $key => $item)
        {
            if ($item != '' && $this->input('external' . $key) != '1')
            {
                array_push($member_collection, $item);
            }
        }
        array_push($member_collection, Auth::user()->nidn);
        $member_unique_collection = array_unique($member_collection);
        if (count($member_unique_collection) !== count($member_collection))
        {
            array_push($ret, 'Anggota yang dipilih tidak boleh duplikasi');
        }

        //Check Head Dedication Creation times
//        if ($this->input('is_own') !== '1')
//        {
//            $i_as_member = 0;
//            $i_as_head = 0;
//            $year = date('Y', strtotime(Carbon::now()->toDateString()));
//            $periods = Period::where('years', $year)->get();
//            foreach ($periods as $period)
//            {
//                $propose = $period->propose()->where('created_by', Auth::user()->nidn)->where('is_own', null)->get();
//                foreach ($propose as $item)
//                {
//                    $flow_status = $item->flowStatus()->where('status_code', '<>', 'UT')->where('status_code', '<>', 'SS')->first();
//                    if ($flow_status !== null)
//                    {
//                        $i_as_head++;
//                        if ($i_as_head >= 2)
//                        {
////                            array_push($ret, '1 Dosen hanya bisa menjadi ( ketua penlitian sebanyak 1 kali dan menjadi anggota sebanyak 2 kali ) atau ( anggota sebanyak 3 kali ) dalam 1 tahun');
//                            array_push($ret, 'Batas maksimal untuk mengajukan usulan penelitian sudah mencapai batas! (2 kali ketua)');
//                            break 2;
//                        }
//                    }
//                }
//
//                $proposes = $period->propose()->where('created_by', '<>', Auth::user()->nidn)->where('is_own', null)->get();
//                foreach ($proposes as $propose)
//                {
//                    $flow_status = $propose->flowStatus()->where('status_code', '<>', 'UT')->where('status_code', '<>', 'SS')->first();
//                    if ($flow_status !== null)
//                    {
//                        $member = $propose->member()->where('nidn', Auth::user()->nidn)->where('status', 'accepted')->first();
//                        if ($member !== null)
//                        {
//                            $i_as_member++;
//                            $head_n_member = $i_as_head + $i_as_member;
//                            if ($head_n_member >= 2)
//                            {
////                                array_push($ret, '1 Dosen hanya bisa menjadi ( ketua penlitian sebanyak 1 kali dan menjadi anggota sebanyak 2 kali ) atau ( anggota sebanyak 3 kali ) dalam 1 tahun');
//                                $err_str = 'Batas maksimal untuk mengajukan usulan penelitian sudah mencapai batas! (' . $i_as_member . ' kali anggota';
//                                if ($i_as_head > 0)
//                                {
//                                    $err_str = $err_str . ' & ' . $i_as_head . ' kali ketua)';
//                                } else
//                                {
//                                    $err_str = $err_str . ')';
//                                }
//                                array_push($ret, $err_str);
//                                break 2;
//                            }
//                        }
//                    }
//                }
//            }
//        }
        return $ret;
    }

}
