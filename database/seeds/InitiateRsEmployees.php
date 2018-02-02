<?php

use Illuminate\Database\Seeder;

class InitiateRsEmployees extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->client = new \GuzzleHttp\Client();
        $response = $this->client->get('https://api.usu.ac.id/0.1/users?unit_id=28&type_id=1,5&fieldset=functional');
        $employees = json_decode($response->getBody());
        
        foreach ($employees->data as $employee) 
        {  
            if(isset($employee->nip) && isset($employee->functional))
            {
                if($employee->functional[0]->functional_id=='7' || $employee->functional[0]->functional_id=='9' || 
                    $employee->functional[0]->functional_id=='11' || $employee->functional[0]->functional_id=='14')
                {
                    $store = new \App\User();
                    $find = $store->where('nidn', $employee->nip)->first();
                    if ($find === null)
                    {
                        $find = $store->create([
                            'nidn'     => $employee->nip,
                            'password' => '$2y$10$NrTbRzTKnPrIm3OBKcMOSOzdnXXSIyhNEHM7RDtTuDZOBvmhsErO2',
                        ]);
                    }
                    $auth = \App\Auths::where('user_id', $find->id)->where('auth_object_ref_id', '5')->first();
                    if ($auth === null)
                    {
                        \App\Auths::create([
                            'user_id'            => $find->id,
                            'auth_object_ref_id' => '5',
                            'begin_date'         => '2000-01-01',
                            'end_date'           => '9999-12-31',
                            'created_by'         => 'admin'
                        ]);
                    }
                }
            }
        }
    }
}
