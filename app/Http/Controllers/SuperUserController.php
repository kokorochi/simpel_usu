<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Input;

class SuperUserController extends Controller
{
    public function generateLecturer()
    {
        $lecturers = \App\ModelSDM\Lecturer::all();
        foreach ($lecturers as $lecturer)
        {
            $store = new \App\User();
            $find = $store->where('nidn', $lecturer->employee_card_serial_number)->first();
            if ($find === null)
            {
                $find = $store->create([
                    'nidn'     => $lecturer->employee_card_serial_number,
                    'password' => $lecturer->password,
                ]);
            }
            $auth = \App\Auths::where('user_id', $find->id)->where('auth_object_ref_id', '4')->first();
            if ($auth === null)
            {
                \App\Auths::create([
                    'user_id'            => $find->id,
                    'auth_object_ref_id' => '4',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin'
                ]);
            }
        }
    }

    public function showResetPassword()
    {
        return view('superuser.reset-password');
    }

    public function resetPassword()
    {
        $input = Input::get();
        $user = User::where('nidn', $input['nidn'])->first();
        if (! is_null($user))
        {
            $user->password = bcrypt($input['password']);
            $saved = $user->save();
            if ($saved)
            {
                return "Password changed";
            }
        }

        return "Something Wrong";
    }
}
