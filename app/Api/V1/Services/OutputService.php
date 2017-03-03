<?php

namespace App\Api\V1\Services;

use App\ModelSDM\Faculty;
use App\ModelSDM\Lecturer;
use App\ModelSDM\StudyProgram;
use App\Output_type;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class OutputService {

    private $database;
    private $dispatcher;

    public function __construct(
        DatabaseManager $databaseManager,
        Dispatcher $dispatcher
    )
    {
        $this->database = $databaseManager;
        $this->dispatcher = $dispatcher;
    }

    public function getCountAcceptedOutput(
        $level = 1,
        $input_faculty_code = [],
        $input_study_program = [],
        $input_lecturer = [],
        $input_output_code = [],
        $input_years = []
    )
    {
        if (1 == 1)
        {
            //Filter Faculty
            $faculties = Faculty::query();

            //Setup query
            if (! empty($input_faculty_code) && is_array($input_faculty_code))
                $faculties->whereIn('faculty_code', $input_faculty_code);
            $faculties->where('is_faculty', '1')->where('faculty_code', '<>', 'SPS');
            //End query

            $faculties = $faculties->get();

            //Change filter value of faculty
            $input_faculty_code = [];
            foreach ($faculties as $faculty)
            {
                $input_faculty_code[] = $faculty->faculty_code;
            }
            //End filter

            //Filter Study Program
            if ($level > 1)
            {
                $study_programs = StudyProgram::query();

                //Setup query
                if (! empty($input_faculty_code))
                    $study_programs->whereIn('faculty_code', $input_faculty_code);
                if (! empty($input_study_program) && is_array($input_study_program))
                    $study_programs->whereIn('study_program', $input_study_program);
                //End query

                $study_programs = $study_programs->get();

                //Change filter value of faculty and study program based on the study program that is available
                $input_faculty_code = [];
                $input_study_program = [];
                foreach ($study_programs as $item)
                {
                    $input_faculty_code[] = $item->faculty_code;      //Filter faculty
                    $input_study_program[] = $item->study_program;    //Filter study program
                }
                $input_faculty_code = array_unique($input_faculty_code);
            }

            //Filter Lecturer
            $lecturers = Lecturer::query();
            if ($level > 1)
            {
                //Setup query
                if (! empty($input_study_program) && is_array($input_study_program))
                    $lecturers->whereIn('study_program', $input_study_program);
                foreach ($input_lecturer as $key1 => $item1)
                {
                    if (is_array($item1) && ! empty($item1))
                    {
                        //Parameter grouping for the same fields
                        $lecturers->where(function ($query) use ($item1, $key1)
                        {
                            foreach ($item1 as $item)
                            {
                                $query->orWhere($key1, 'like', '%' . $item . '%');
                            }
                        });
                        //End parameter grouping
                    }
                }
                //End query

                $lecturers = $lecturers->get([
                    'full_name',
                    'number_of_employee_holding',
                    'employee_card_serial_number'
                ]);
                foreach ($lecturers as $lecturer)
                {
                    $filter['lecturer'][] = $lecturer->employee_card_serial_number;
                }
            }

            //Filter Output Type
            $output_types = Output_type::query();

            //Setup query
            if (! empty($input_output_code) && is_array($input_output_code))
                $output_types->whereIn('output_code', $input_output_code);
            //End query

            $output_types = $output_types->get([
                'id',
                'output_code',
                'output_name'
            ]);

            foreach ($output_types as $output_type)
            {
                $filter['output_type_id'][] = $output_type->id;
            }
            //End filter
        }

        if (1 == 1)
        {
            $query = DB::table('propose_output_types')
                ->join('output_types', 'output_types.id', '=', 'propose_output_types.output_type_id')
                ->join('proposes', 'proposes.id', '=', 'propose_output_types.propose_id')
                ->join('researches', 'researches.propose_id', '=', 'proposes.id')
                ->join('research_output_generals', 'research_output_generals.research_id', '=', 'researches.id')
                ->join('output_flow_statuses', 'output_flow_statuses.research_id', '=', 'researches.id');

            if (! empty($filter['lecturer']) && is_array($filter['lecturer']))
                $query->whereIn('proposes.created_by', $filter['lecturer']);
            if (! empty($filter['output_type_id']) && is_array($filter['output_type_id']))
                $query->whereIn('propose_output_types.output_type_id', $filter['output_type_id']);
            if (! empty($input_years) && is_array($input_years))
                $query->whereIn('research_output_generals.year', $input_years);
            $query->whereIn('output_flow_statuses.status_code', ['LT'])
                ->select('proposes.created_by', DB::raw('COUNT(DISTINCT(propose_output_types.id)) AS count_output'))
                ->groupBy('proposes.created_by');

            if($level == 4)
                $query->addSelect('output_types.output_code', 'output_types.output_name')
                    ->groupBy('output_types.output_code', 'output_types.output_name');

//            dd($query->toSql());
            $query = $query->get();
        }

        $result['level'] = $level;
        $result['year'] = $input_years;
        $result['faculty'] = $input_faculty_code;
        $result['study_program'] = $input_study_program;
        $result['lecturer'] = $lecturers;
        $result['output_type'] = $output_types;
        if(!$query->isEmpty())
            $result['count_output'] = $query[0]->count_output;

        return $result;
    }
}