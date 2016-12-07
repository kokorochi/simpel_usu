<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = new App\User;
        $store->create([
            'nidn'      => '0010018006',
            'password'  => bcrypt('anggi')
        ]);
        $store->create([
            'nidn'      => '0012025903',
            'password'  => bcrypt('member')
        ]);
        $store->create([
            'nidn'      => '0018074905',
            'password'  => bcrypt('member')
        ]);
        $store->create([
            'nidn'      => '0016054801',
            'password'  => bcrypt('reviewer')
        ]);
        $store->create([
            'nidn'      => '196611101989031001',
            'password'  => bcrypt('operator')
        ]);
    }
}
