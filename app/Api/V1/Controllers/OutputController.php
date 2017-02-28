<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Services\OutputService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class OutputController extends Controller {
    private $outputService;

    public function __construct(OutputService $outputService)
    {
        $this->outputService = $outputService;
    }

    public function getCountAcceptedOutput()
    {
        $input['level'] = Input::get('level');
        $input['faculty_code'] = Input::get('faculty_code');
        $input['study_program'] = Input::get('study_program');
        $input['lecturer']['full_name'] = Input::get('lecturer_full_name');
        $input['lecturer']['number_of_employee_holding'] = Input::get('lecturer_nip');
        $input['lecturer']['employee_card_serial_number'] = Input::get('lecturer_nidn');
        $input['output_code'] = Input::get('output_code');
        $input['years'] = Input::get('years');

        $output = $this->outputService->getCountAcceptedOutput(
            $input['level'],
            $input['faculty_code'],
            $input['study_program'],
            $input['lecturer'],
            $input['output_code'],
            $input['years']
        );

        return response($output, 200)->header('Content-Type', 'application/json');
    }
}