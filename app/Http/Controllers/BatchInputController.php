<?php

namespace App\Http\Controllers;

use App\FlowStatus;
use App\Member;
use App\Output_type;
use App\Propose;
use App\Propose_own;
use App\ProposeOutputType;
use App\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\FunctionClass\UserListImport;
use Illuminate\Support\Facades\Storage;
use View;

class BatchInputController extends BlankonController {
    protected $pageTitle = 'Batch Input';

    public function __construct()
    {
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jquery-ui/themes/base/jquery-ui.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.jquery.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/jquery-ui.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/ui/minified/autocomplete.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-autosize/jquery.autosize.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);
        View::share('pageTitle', $this->pageTitle);
    }

    public function selectFile()
    {
        return view('batch-input.batch-input-select');
    }

    public function preview(UserListImport $import)
    {
        $excel_results = $import->get()->toArray();

        $pageTitle = 'Preview';

        foreach ($excel_results as $key => $excel_result)
        {
            $excel_results[$key]['research_type'] = $this->splitString($excel_result['research_type'], "-");
            $excel_results[$key]['output_type_id_1'] = $this->splitString($excel_result['output_type_id_1'], "-");
            $excel_results[$key]['output_type_id_2'] = $this->splitString($excel_result['output_type_id_2'], "-");
            $excel_results[$key]['output_type_id_3'] = $this->splitString($excel_result['output_type_id_3'], "-");
            $excel_results[$key]['faculty_code'] = $this->splitString($excel_result['faculty_code'], ":");

            $length = strlen($excel_result['created_by']);
            while ($length < 10)
            {
                $excel_results[$key]['created_by'] = '0' . $excel_results[$key]['created_by'];
                $length++;
            }

            $i = 1;
            while ($i <= 5)
            {
                if ($excel_result['nidn_' . $i] == '0' || $excel_result['nidn_' . $i] == '')
                    $excel_results[$key]['nidn_' . $i] = '';
                else
                {
                    $length = strlen($excel_result['nidn_' . $i]);
                    while ($length < 10)
                    {
                        $excel_results[$key]['nidn_' . $i] = '0' . $excel_results[$key]['nidn_' . $i];
                        $length++;
                    }
                }
                $i++;
            }
        }

        return view('batch-input.batch-input-preview', compact(
            'pageTitle',
            'excel_results'
        ));
    }

    public function upload(Request $request)
    {
        $count_uploaded = 0;
        foreach ($request->years as $key => $year)
        {
            DB::transaction(function () use ($key, $request, $count_uploaded)
            {
                $propose = new Propose();
                $propose->is_own = 1;
                $propose->faculty_code = $request->faculty_code[$key];
                if ($request->areas_of_expertise[$key] == '0')
                    $propose->areas_of_expertise = '-';
                else
                    $propose->areas_of_expertise = $request->areas_of_expertise[$key];
                $propose->title = $request->title[$key];
                $propose->total_amount = $request->total_amount[$key];
                $propose->final_amount = $request->total_amount[$key];
                $propose->time_period = $request->time_period[$key];
                $propose->student_involved = $request->student_involved[$key];
                $propose->address = $request->address[$key];
                $propose->created_by = $request->created_by[$key];
                $propose->approval_status = "Accepted";

                $propose_own = new Propose_own();
                $propose_own->years = $request->years[$key];
                $propose_own->research_type = $request->research_type[$key];
                $propose_own->scheme = $request->scheme[$key];
                $propose_own->sponsor = $request->sponsor[$key];
                $propose_own->member = $request->count_member[$key];

                if ($propose_own->member > 0 && $propose_own->member <= 5)
                {
                    $members = new Collection();
                    $i = 1;
                    while ($i <= $propose_own->member)
                    {

                        $member = new Member();
                        $member->item = $i;
                        $member->nidn = $request['nidn_' . $i][$key];
                        if ($request['areas_of_expertise_' . $i][$key] == '0' || $request['areas_of_expertise_' . $i][$key] == '')
                            $member->areas_of_expertise = '-';
                        else
                            $member->areas_of_expertise = $request['areas_of_expertise_' . $i][$key];
                        $member->status = 'accepted';
                        $members->push($member);
                        $i++;
                    }
                }

                $propose_output_types = new Collection();
                $i = 1;
                while ($i <= 3)
                {
                    if ($request['output_type_id_' . $i][$key] != "0")
                    {
                        $propose_output_type = new ProposeOutputType();
                        $output_type = Output_type::where('output_code', $request['output_type_id_' . $i][$key])->first();
                        if (! is_null($output_type))
                        {
                            $propose_output_type->item = $i;
                            $propose_output_type->output_type_id = $output_type->id;
                            $propose_output_types->push($propose_output_type);
                        }
                    }
                    $i++;
                }

                $research = new Research();
                $research->created_by = $propose->created_by;

                $flow_statuses = new Collection();

                $flow_status = new FlowStatus();
                $flow_status->item = '1';
                $flow_status->status_code = 'BI'; //Batch Input
                $flow_status->created_by = Auth::user()->nidn;
                $flow_statuses->push($flow_status);

                $flow_status = new FlowStatus();
                $flow_status->item = '2';
                $flow_status->status_code = 'UL'; //Menunggu Luaran
                $flow_status->created_by = Auth::user()->nidn;
                $flow_statuses->push($flow_status);

                $saved = $propose->save();
                if ($saved)
                {
                    $propose->proposesOwn()->save($propose_own);
                    if(isset($members) && !$members->isEmpty())
                        $propose->member()->saveMany($members);
                    $propose->proposeOutputType()->saveMany($propose_output_types);
                    $propose->research()->save($research);
                    $propose->flowStatus()->saveMany($flow_statuses);
                }
            });
            $count_uploaded++;
        }

        return $count_uploaded . " Successfully Uploaded! <a href='" . url('batch-input') . "'>back</a>";
    }

    private function splitString($p_string, $p_delimeter)
    {
        $pos = strpos($p_string, $p_delimeter);
        if ($pos === false)
        {
            return $p_string;
        } else
        {
            $res = substr($p_string, 0, $pos);
            $res = rtrim($res);

            return $res;
        }
    }
}
