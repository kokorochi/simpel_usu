<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use App\Auths;
use App\ModelSDM\Faculty;
use App\ModelSDM\Lecturer;
use App\ModelSDM\StudyProgram;
use App\Output_type;
use App\Period;
use App\Propose;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AJAXController extends BlankonController {
    public function __construct()
    {
        parent::__construct();
    }

    public function getPeriod()
    {
        $period_id = Input::get('period_id');

        $period = Period::find($period_id);
        $period->category_name = $period->categoryType->category_name;
        $period->research_name = $period->researchType->research_name;

        $period = json_encode($period, JSON_PRETTY_PRINT);

        return response($period, 200)->header('Content-Type', 'application/json');
    }

    public function searchLecturer()
    {
        $input = '%' . Input::get("key_input") . '%';
        $lecturers = Lecturer::where('full_name', 'LIKE', $input)
            ->orWhere('employee_card_serial_number', 'LIKE', $input)->take(10)->get();

        foreach ($lecturers as $lecturer)
        {
            $lecturer->full_name = $lecturer->employee_card_serial_number . ' : ' . $lecturer->full_name;
        }

        $lecturers = json_encode($lecturers, JSON_PRETTY_PRINT);

        return response($lecturers, 200)->header('Content-Type', 'application/json');
    }

    public function getLecturerByNIDN()
    {
        $input = Input::get("key_input");
        $lecturer = Lecturer::where('employee_card_serial_number', $input)->first();


//        return json_encode($lecturer, JSON_PRETTY_PRINT);
        $lecturer = json_encode($lecturer, JSON_PRETTY_PRINT);

//
        return response($lecturer, 200)->header('Content-Type', 'application/json');
    }

    public function getProposesByScheme()
    {
        $period_id = Input::get("period_id");
        $status_codes = Input::get("status_code");
        $type = Input::get("type");
        if ($period_id == 0)
        {
            $proposes = Propose::where('is_own', '1')->get();
        } else
        {
            $period = Period::find($period_id);
            $proposes = $period->propose()->get();
        }
        $proposes_final = new Collection;
        foreach ($proposes as $propose)
        {
            $status_code = $propose->flowStatus()->orderBy('item', 'desc')->first()->status_code;
            $propose->status_code = $status_code;
            if ($status_codes !== null)
            {
                foreach ($status_codes as $item)
                {
                    if ($status_code === $item)
                    {
                        $proposes_final->add($propose);
                    }
                }
            } else
            {
                if ($status_code !== 'SS') //Skip if status is Simpan Sementara
                {
                    $proposes_final->add($propose);
                }
            }
        }

        $i = 0;
        $data = [];
        foreach ($proposes_final as $propose)
        {
            $data['data'][$i][0] = $propose->title;
            $data['data'][$i][1] = Lecturer::where('employee_card_serial_number', $propose->created_by)->first()->full_name;
            if ($propose->is_own === '1')
            {
                $data['data'][$i][2] = $propose->proposesOwn()->first()->scheme;
            } else
            {
                $data['data'][$i][2] = $period->scheme;
            }
            $data['data'][$i][3] = $propose->flowStatus()->orderBy('item', 'desc')->first()->statusCode()->first()->description;
            if ($type === 'ASSIGN')
            {
                $data['data'][$i][4] = '<td class="text-center"><a href="' . url('reviewers/assign', $propose->id) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Assign"><i class="fa fa-plus-circle"></i></a></td>';
            } elseif ($type === 'APPROVE')
            {
                if ($propose->status_code === 'RS')
                {
                    $data['data'][$i][4] = '<td class="text-center"><a href="' . url('approve-proposes', $propose->id) . '/approve' . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Approve"><i class="fa fa-check-square-o"></i></a></td>';
                } else
                {
                    $data['data'][$i][4] = '<td class="text-center"><a href="' . url('approve-proposes', $propose->id) . '/display' . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Detail"><i class="fa fa-search-plus"></i></a></td>';
                }
            }

            $i++;
        }
        $count_data = count($data);
        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getReviewer()
    {
//        $key_input = Input::get('key_input');
        $reviewers = Auths::where('auth_object_ref_id', '3')->get();
        $i = 0;
        foreach ($reviewers as $reviewer)
        {
            $lecturer = $reviewer->user()->first()->lecturer()->first();
            $data[$i]['nidn'] = $lecturer->employee_card_serial_number;
            $data[$i]['full_name'] = $lecturer->full_name;
            $i++;
        }
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function searchReviewer()
    {
        $input = Input::get("key_input");
        $reviewers = Auths::where('auth_object_ref_id', '3')->get();
        $i = 0;
        $data = [];
        foreach ($reviewers as $reviewer)
        {
            $lecturer = $reviewer->user()->first()->lecturer()->first();
            if (stripos($lecturer->full_name, $input) !== false ||
                stripos($lecturer->employee_card_serial_number, $input) !== false
            )
            {
                $data[$i]['nidn'] = $lecturer->employee_card_serial_number;
                $data[$i]['full_name'] = $lecturer->full_name;
                $i++;
            }
        }
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getResearch()
    {
        $period_id = Input::get("period_id");
        $review_by = Input::get("review_by");
        if ($period_id == 0)
        {
            $proposes = Propose::where('is_own', '1')->get();
        } else
        {
            $period = Period::find($period_id);
            $proposes = $period->propose()->get();
        }

        $researches = new Collection();
        foreach ($proposes as $propose)
        {
            $research = $propose->research()->first();
            if ($research !== null)
            {
                if ($review_by !== null)
                {
                    $research_reviewer = $propose->researchReviewer()->where('nidn', $review_by)->first();
                    if ($research_reviewer !== null)
                    {
                        $researches->add($research);
                    }
                } else
                {
                    $researches->add($research);
                }
            }
        }

        $i = 0;
        $data = [];
        foreach ($researches as $research)
        {
            $propose = $research->propose()->first();
            $data['data'][$i][0] = $propose->title;
            $data['data'][$i][1] = Lecturer::where('employee_card_serial_number', $propose->created_by)->first()->full_name;
            if ($propose->is_own === '1')
            {
                $data['data'][$i][2] = $propose->proposesOwn()->first()->scheme;
            } else
            {
                $data['data'][$i][2] = $period->scheme;
            }
            $data['data'][$i][3] = $propose->flowStatus()->orderBy('item', 'desc')->first()->statusCode()->first()->description;
            if ($review_by === null)
            {
                $data['data'][$i][4] = '<td class="text-center"><a href="' . url('researches', $research->id) . '/approve' . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Approve"><i class="fa fa-check-square-o"></i></a></td>';
            } else
            {
                $data['data'][$i][4] = '<td class="text-center"><a href="' . url('review-proposes', $research->id) . '/research-display' . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Display"><i class="fa fa-search-plus"></i></a></td>';
            }
            $i++;
        }
        $count_data = count($data);
        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getFaculty()
    {
        $input = Input::get('input');
        $query = Faculty::query();
        if (! is_null($input))
        {
            if (is_array($input))
            {
                foreach ($input as $key => $item)
                {
                    $query->orWhere($key, 'like', '%' . $item . '%');
                }
            }
        }
        $query->where('is_faculty', '1')->where('faculty_code', '<>', 'SPS');
        $data['data'] = $query->get(['faculty_code', 'faculty_name']);

        if ($data['data']->isEmpty()) $count_data = 0;
        else $count_data = count($data['data']);

        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getStudyProgram()
    {
        $input = Input::get('faculty_code');
        $query = StudyProgram::query();
        if (! is_null($input))
        {
            if (is_array($input))
            {
                foreach ($input as $key => $item)
                {
                    $query->orWhere('faculty_code', 'like', '%' . $item . '%');
                }
            }
        }
        $data['data'] = $query->get();

        if ($data['data']->isEmpty()) $count_data = 0;
        else $count_data = count($data['data']);

        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getLecturer()
    {
        $input = Input::get();
        $query = Lecturer::query();
        foreach ($input as $key1 => $item1)
        {
            if (is_array($item1))
            {
                $first = true;
                foreach ($item1 as $item)
                {
                    if ($first)
                    {
                        $first = false;
                        $query->where($key1, 'like', '%' . $item . '%');
                    } else
                    {
                        $query->orWhere($key1, 'like', '%' . $item . '%');
                    }
                }
            } else
            {
                $query->where($key1, 'like', '%' . $item . '%');
            }
        }
        $data['data'] = $query->get();

        if ($data['data']->isEmpty()) $count_data = 0;
        else $count_data = count($data['data']);

        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getOutput()
    {
        $input = Input::get();
        $query = Output_type::query();
        foreach ($input as $key1 => $item1)
        {
            if (is_array($item1))
            {
                $first = true;
                foreach ($item1 as $item)
                {
                    if ($first)
                    {
                        $first = false;
                        $query->where($key1, 'like', '%' . $item . '%');
                    } else
                    {
                        $query->orWhere($key1, 'like', '%' . $item . '%');
                    }
                }
            } else
            {
                $query->where($key1, 'like', '%' . $item . '%');
            }
        }
        $data['data'] = $query->get(['id', 'output_code', 'output_name']);

        if ($data['data']->isEmpty()) $count_data = 0;
        else $count_data = count($data['data']);

        if ($count_data == 0)
        {
            $data['data'] = [];
        }
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $count_data;
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function getCountOutput()
    {
        $input = Input::get();

        if (! isset($input['level']))
        {
            $input['level'] = 1;
        }

        if (! isset($input['year']))
        {
            $input['year'] = date('Y');
        }
        $min_year = $input['year'] - 3;

        $query = Output_type::query();
        if (isset($input['output_code']))
        {
            if (is_array($input['output_code']))
            {
                $query->whereIn('output_code', $input['output_code']);
            } else
            {
                $query->where('output_code', $input['output_code']);
            }
        }
        $output_types = $query->get();
        foreach ($output_types as $output_type)
        {
            $filter['output_type'][] = $output_type->id;
        }

        if ($input['level'] == 1) // University level means list all faculty
        {
            $data['columns'][] = [
                'label'    => 'Fakultas',
                'property' => 'faculty_name',
                'sortable' => true,
            ];

            while ($min_year <= $input['year'])
            {
                $data['columns'][] = [
                    'label'    => $min_year,
                    'property' => 'year_' . $min_year,
                    'sortable' => true,
                    'cssClass' => 'report-year'
                ];
                $min_year++;
            }

            $faculties = Faculty::where('is_faculty', '1')->where('faculty_code', '<>', 'SPS')->get();
            $i = 0;
            foreach ($faculties as $faculty)
            {
                $lecturers = Lecturer::where('work_unit', $faculty->faculty_code)->get(["employee_card_serial_number"]);
                foreach ($lecturers as $lecturer)
                {
                    $filter['lecturer'][] = $lecturer->employee_card_serial_number;
                }

                $data['items'][$i]['faculty_name'] = $faculty->faculty_name;

                $min_year = $input['year'] - 3;
                while ($min_year <= $input['year'])
                {
//                    $query = DB::table('proposes')
//                        ->join('propose_output_types', 'propose_output_types.propose_id', '=', 'proposes.id')
//                        ->join('researches', 'researches.propose_id', '=', 'propose.id');

                    $query = DB::table('output_flow_statuses')
                        ->join('researches', 'researches.id', '=', 'output_flow_statuses.research_id')
                        ->join('proposes', 'proposes.id', '=', 'researches.propose_id')
                        ->join('propose_output_types', 'propose_output_types.propose_id', '=', 'proposes.id')
                        ->join('research_output_generals', 'research_output_generals.research_id', '=', 'researches.id');

                    if (isset($filter['lecturer']) && is_array($filter['lecturer']))
                        $query->whereIn('proposes.created_by', $filter['lecturer']);
                    if (isset($filter['output_type']) && is_array($filter['output_type']))
                        $query->whereIn('propose_output_types.output_type_id', $filter['output_type']);
                    $query->where('output_flow_statuses.status_code', 'VL');
                    $query->where('research_output_generals.year', $min_year);
                    $query->select('proposes.*', 'research_output_generals.*');
                    $query = $query->get();
                    if ($faculty->faculty_code == 'FIKTI' && $min_year == 2017)
                    {
                        dd($query);
                    }

                    $data['items'][$i]['year_' . $min_year] = rand(0, 3);
                    $min_year++;
                }
                $data['items'][$i]['faculty_name'] = $faculty->faculty_name;
                $i++;
            }
        }
        $data['total'] = count($data['items']);
        $data['input'] = $input;

        $data = json_encode($data, JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');

//        $res = $client->request('GET', 'http://simpel.usu.ac.id/api/outputs/count/search',[
//          'query' => [
//              'level' => '1',
//              'years[]' => '2013',
//              'years[]' => '2014',
//              'years[]' => '2015',
//              'years[]' => '2016',
//              'output_code[]' => 'PFN',
//              'faculty_code[]' => 'FIKTI',
//          ]
//        ]);
//        echo $res->getStatusCode();
    }
}