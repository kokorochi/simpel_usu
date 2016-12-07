<?php

use Illuminate\Database\Seeder;

class AuthsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = new App\Auths;
        $users = new App\User;
        $users = $users->all();
        foreach ($users as $user)
        {
            if ($user->nidn === '0010018006') //Super User
            {
                $store->create([
                    'user_id'            => $user->id,
                    'auth_object_ref_id' => '1',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin',
                ]);
            } elseif ($user->nidn === '196611101989031001') //Operator
            {
                $store->create([
                    'user_id'            => $user->id,
                    'auth_object_ref_id' => '2',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin',
                ]);
            } elseif ($user->nidn === '0016054801') //Reviewer
            {
                $store->create([
                    'user_id'            => $user->id,
                    'auth_object_ref_id' => '3',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin',
                ]);
            } elseif ($user->nidn === '0012025903') //Dosen
            {
                $store->create([
                    'user_id'            => $user->id,
                    'auth_object_ref_id' => '4',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin',
                ]);
            } elseif ($user->nidn === '0018074905') //Dosen
            {
                $store->create([
                    'user_id'            => $user->id,
                    'auth_object_ref_id' => '4',
                    'begin_date'         => '2000-01-01',
                    'end_date'           => '9999-12-31',
                    'created_by'         => 'admin',
                ]);
            }
        }
    }
}
