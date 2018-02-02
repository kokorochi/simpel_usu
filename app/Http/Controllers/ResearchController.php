<?php

namespace App\Http\Controllers;

use App\OutputFlowStatus;
use App\OutputMember;
use App\Research;
use App\Period;
use App\Propose;
use App\Member;
use App\ModelSDM\Lecturer;
use App\ModelSDM\Faculty;
use App\Output_type;
use App\ResearchOutputGeneral;
use App\ResearchOutputRevision;
use App\ResearchType;
use App\Propose_own;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Http\Requests;
use App\Announce;
use Illuminate\Support\Facades\DB;
use View;

class ResearchController extends BlankonController {
    protected $pageTitle = 'Penelitian';
    protected $deleteQuestion = '';
    protected $deleteUrl = 'researches';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isLecturer')->except(['approveList', 'approveDetail', 'approveUpdate', 'getOutputFile']);
        $this->middleware('isOperator')->only(['approveList', 'approveDetail', 'approveUpdate']);
        $this->middleware('isLecturerOrOperator')->only(['getOutputFile']);
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jquery-ui/themes/base/jquery-ui.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/css/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/css/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/fuelux/dist/css/fuelux.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.jquery.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/jquery-ui.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/ui/minified/autocomplete.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/dataTables.bootstrap.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/datatables.responsive.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');

        array_push($this->js['scripts'], 'admin/js/pages/blankon.form.advanced.js');
        array_push($this->js['scripts'], 'admin/js/pages/blankon.form.element.js');
        array_push($this->js['scripts'], 'admin/js/pages/blankon.form.picker.js');
        array_push($this->js['scripts'], 'admin/js/datatable-custom.js');
        array_push($this->js['scripts'], 'admin/js/customize.js');
        array_push($this->js['scripts'], 'admin/js/search-member.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('pageTitle', $this->pageTitle);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);
        View::share('deleteUrl', $this->deleteUrl);
        View::share('deleteQuestion', $this->deleteQuestion);
    }

    public function index()
    {
        $proposes = Propose::where('created_by', Auth::user()->nidn)->get();

        $members = Member::where('nidn', Auth::user()->nidn)->where('status', '<>', 'rejected')->get();
        foreach ($members as $member)
        {
            $propose = $member->propose()->first();
            if ($propose !== null)
            {
                $proposes->add($propose);
            }
        }

        $output_members = OutputMember::where('nidn', Auth::user()->nidn)->get();
        if(isset($_GET['test'])){
            dd($output_members);
        }
        
        foreach ($output_members as $output_member)
        {
            $research_output_general = $output_member->researchOutputGeneral()->first();
            $research = $research_output_general->research()->first();
            if ($research !== null)
            {
                $propose = $research->propose()->first();
                if ($propose !== null)
                {
                    $find_propose = $proposes->find($propose->id);
                    if ($find_propose === null)
                    {
                        $flow_status = $propose->flowStatus()->orderBy('id', 'desc')->first();
                        if ($flow_status->status_code === 'PS')
                        {
                            $proposes->add($propose);
                        }
                    }
                }
            }
        }

        $researches = new Collection();
        foreach ($proposes as $propose)
        {
            $research = $propose->research()->first();
            if ($research !== null) $researches->add($research);
        }

        foreach ($researches as $research)
        {
            $output_status = '';
            $research_output_generals = $research->researchOutputGeneral()->get();
            if ($research_output_generals->isEmpty())
            {
                $research->output_status = 'Luaran belum diunggah';
            } else
            {
                $output_flow_status = $research->outputFlowStatus()->orderBy('id', 'desc')->first();
                if ($output_flow_status !== null &&
                    $output_flow_status->status_code !== null &&
                    $output_flow_status->status_code != 'UL' )
                {
                    $output_status = $output_flow_status->statusCode()->first()->description;
                } else
                {
                    foreach ($research_output_generals as $research_output_general)
                    {
                        if ($output_status == '')
                        {
                            $output_status = $research_output_general->output_description . ': ' . $research_output_general->status;
                        } else
                        {
                            $output_status = $output_status . '<br />' . $research_output_general->output_description . ': ' . $research_output_general->status;
                        }
                    }
                }
                $research->output_status = $output_status;
            }
        }

        $researches = new Paginator($researches, 10, Input::get('page', 1));

        $data_not_found = 'Data tidak ditemukan';

        return view('research.research-list', compact(
            'researches',
            'data_not_found'
        ));
    }

    public function display($id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $propose = $research->propose()->first();
        if ($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $propose_relation = $this->getProposeRelationData($propose);
        $propose_relation->propose = $propose;

        if ($propose->created_by !== Auth::user()->nidn)
        {
            $member = $propose_relation->members->filter(function ($item)
            {
                return $item->nidn = Auth::user()->nidn;
            })->first();

            if (is_null($member))
            {
                $this->setCSS404();

                return abort('403');
            }
        }

        if ($propose_relation->flow_status->status_code === 'UD')
        {
            $research->propose()->first()->flowStatus()->create([
                'item'        => $propose_relation->flow_status->item + 1,
                'status_code' => 'LK', //Menunggu Laporan Kemajuan
                'created_by'  => Auth::user()->nidn,
            ]);
            $propose_relation->flow_status->status_code = 'LK';
        }

        $disable_upload = false;
        $status_code = $propose_relation->flow_status->status_code;
        if ($status_code !== 'UU' && $status_code !== 'PR')
        {
            $disable_upload = true;
        }

        $disabled = 'disabled';
        $disable_final_amount = 'readonly';
        $upd_mode = 'display';

        return view('research.research-edit', compact(
            'research',
            'propose_relation',
            'disable_upload',
            'status_code',
            'disable_final_amount',
            'disabled',
            'upd_mode'
        ));
    }

    public function edit($id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $propose = $research->propose()->first();
        if ($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        if ($propose->created_by !== Auth::user()->nidn)
        {
            $this->setCSS404();

            return abort('403');
        }

        $propose_relation = $this->getProposeRelationData($propose);
        $propose_relation->propose = $propose;

        if ($propose_relation->flow_status->status_code === 'UD')
        {
            $research->propose()->first()->flowStatus()->create([
                'item'        => $propose_relation->flow_status->item + 1,
                'status_code' => 'LK', //Menunggu Laporan Kemajuan
                'created_by'  => Auth::user()->nidn,
            ]);
            $propose_relation->flow_status->status_code = 'LK';
        }

        $disable_upload = false;
        $status_code = $propose_relation->flow_status->status_code;
        if ($status_code !== 'UU' && $status_code !== 'PR')
        {
            $disable_upload = true;
        }

        $disabled = 'disabled';
        $disable_final_amount = 'readonly';
        $upd_mode = 'edit';

        return view('research.research-edit', compact(
            'research',
            'propose_relation',
            'disable_upload',
            'status_code',
            'disable_final_amount',
            'disabled',
            'upd_mode'
        ));
    }

    public function updateProgress(Requests\StoreUpdateProgressRequest $request, $id)
    {
        $research = Research::find($id);

        DB::transaction(function () use ($research, $request)
        {
            $path = Storage::url('upload/' . md5($research->propose()->first()->created_by) . '/progress/');
            if ($research->file_progress_activity !== null) //Delete old propose that already uploaded
            {
                Storage::delete($path . $research->file_progress_activity);
                Storage::delete($path . $research->file_progress_budgets);
            }

            $research->file_progress_activity_ori = $request->file('file_progress_activity')->getClientOriginalName();
            $research->file_progress_activity = md5($request->file('file_progress_activity')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->file_progress_budgets_ori = $request->file('file_progress_budgets')->getClientOriginalName();
            $research->file_progress_budgets = md5($request->file('file_progress_budgets')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->updated_by = Auth::user()->nidn;
            $research->save();

            $request->file('file_progress_activity')->storeAs($path, $research->file_progress_activity);
            $request->file('file_progress_budgets')->storeAs($path, $research->file_progress_budgets);

            $flow_status = $research->propose()->first()->flowStatus()->orderBy('id', 'desc')->first();
            if ($flow_status->status_code === 'LK')
            {
                $research->propose()->first()->flowStatus()->create([
                    'item'        => $flow_status->item + 1,
                    'status_code' => 'LA', //Menunggu Laporan Akhir
                    'created_by'  => Auth::user()->nidn,
                ]);
            }

        });

        return redirect()->intended($this->deleteUrl);
    }

    public function updateFinal(Requests\StoreUpdateFinalRequest $request, $id)
    {
        $research = research::find($id);

        DB::transaction(function () use ($research, $request)
        {
            $path = Storage::url('upload/' . md5($research->propose()->first()->created_by) . '/final/');
            if ($research->file_final_activity !== null) //Delete old propose that already uploaded
            {
                Storage::delete($path . $research->file_final_activity);
                Storage::delete($path . $research->file_final_budgets);
            }

            $propose = $research->propose()->first();
            $research->file_final_activity_ori = $request->file('file_final_activity')->getClientOriginalName();
            $research->file_final_activity = md5($request->file('file_final_activity')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->file_final_budgets_ori = $request->file('file_final_budgets')->getClientOriginalName();
            $research->file_final_budgets = md5($request->file('file_final_budgets')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->updated_by = Auth::user()->nidn;
            $research->save();

            $request->file('file_final_activity')->storeAs($path, $research->file_final_activity);
            $request->file('file_final_budgets')->storeAs($path, $research->file_final_budgets);

            $flow_status = $research->propose()->first()->flowStatus()->orderBy('id', 'desc')->first();
            if ($flow_status->status_code === 'LA')
            {
                $output_flow_status = $research->outputFlowStatus()->orderBy('id', 'desc')->first();
                if ($output_flow_status!== null && $output_flow_status->status_code === 'LT')
                {
                    $research->propose()->first()->flowStatus()->create([
                        'item'        => $flow_status->item + 1,
                        'status_code' => 'PS', //Penelitian Selesai
                        'created_by'  => Auth::user()->nidn,
                    ]);
                    $this->setEmail('PS', $propose);
                } else
                {
                    $research->propose()->first()->flowStatus()->create([
                        'item'        => $flow_status->item + 1,
                        'status_code' => 'UL', //Menunggu Luaran
                        'created_by'  => Auth::user()->nidn,
                    ]);
                    $this->setEmail('UL', $propose);
                }
            }
            $this->setOutputFlowStatuses($research);

        });

        return redirect()->intended($this->deleteUrl);
    }

    public function getFile($id, $type)
    {
        $research = Research::find($id);
        if(is_null($research))
        {
            $this->setCSS404();

            return abort('404');
        }
        $this->authorize('download', $research);
        $propose = $research->propose()->first();
        $nidn = $propose->created_by;
        if ($type == 1)
        {
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/progress/' . $research->file_progress_activity);

            $this->storeDownloadLog($propose->id, 'progress activity', $research->file_progress_activity_ori, $research->file_progress_activity, $nidn);

            return response()->download($path, $research->file_progress_activity_ori, ['Content-Type' => 'application/pdf']);
        } elseif ($type == 2)
        {
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/progress/' . $research->file_progress_budgets);

            $this->storeDownloadLog($propose->id, 'progress budgets', $research->file_progress_budgets_ori, $research->file_progress_budgets, $nidn);

            return response()->download($path, $research->file_progress_budgets_ori, ['Content-Type' => 'application/pdf']);
        } elseif ($type == 3)
        {
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/final/' . $research->file_final_activity);

            $this->storeDownloadLog($propose->id, 'final activity', $research->file_final_activity_ori, $research->file_final_activity, $nidn);

            return response()->download($path, $research->file_final_activity_ori, ['Content-Type' => 'application/pdf']);
        } elseif ($type == 4)
        {
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/final/' . $research->file_final_budgets);

            $this->storeDownloadLog($propose->id, 'final budgets', $research->file_final_budgets_ori, $research->file_final_budgets, $nidn);

            return response()->download($path, $research->file_final_budgets_ori, ['Content-Type' => 'application/pdf']);
        }
    }

    public function output($id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $propose = $research->propose()->first();
        $propose_output_types = $propose->proposeOutputType()->get();

        $status_code = $propose->flowStatus()->orderBy('id', 'desc')->first()->status_code;

        $research_output_generals = $research->researchOutputGeneral()->get();
        if ($research_output_generals->isEmpty())
        {
            $research_output_generals = new Collection();
            $output_members = new Collection();
            foreach ($propose_output_types as $key => $propose_output_type)
            {
                $research_output_general = new ResearchOutputGeneral();
                $research_output_general->output_description = $propose_output_type->outputType()->first()->output_name;
                $research_output_general->status = 'draft';
                $research_output_generals->add($research_output_general);

                $output_members[$key] = new Collection();
                $output_members[$key]->add(new OutputMember);
            }
        } else
        {
            $output_members = new Collection();
            foreach ($research_output_generals as $key => $research_output_general)
            {
                $output_members[$key] = $research_output_general->outputMember()->get();
                if ($output_members[$key]->isEmpty())
                {
//                    $output_members[$key]->add(new OutputMember());
                } else
                {
                    foreach ($output_members[$key] as $output_member)
                    {
                        if ($output_member->nidn !== null) $output_member->nidn_display = $output_member->nidn . ' : ' . Lecturer::where('employee_card_serial_number', $output_member->nidn)->first()->full_name;
                    }
                }
            }
        }

        $research_output_revision = $research->researchOutputRevision()->orderBy('id', 'desc')->first();
        if ($research_output_revision === null) $research_output_revision = new ResearchOutputRevision();

        $disabled = null;
        $output_flow_status = $research->outputFlowStatus()->orderBy('id', 'desc')->first();
        if ($output_flow_status === null)
        {
            $output_flow_status = new OutputFlowStatus();
            $output_flow_status->status_code = 'UL';
        }
        $output_code = $output_flow_status->status_code;
       
        if ($output_flow_status !== null && ($output_code === 'VL' || $status_code === 'PS')) $disabled = 'disabled';
         if(isset($_GET['a'])){
        	dd($output_flow_status);
        }
        $upd_mode = 'output';

        return view('research.research-output', compact(
            'research',
            'propose',
            'propose_output_types',
            'output_members',
            'status_code',
            'output_code',
            'research_output_generals',
            'research_output_revision',
            'upd_mode',
            'disabled'
        ));
    }

    public function updateOutputGeneral(Requests\StoreOutputGeneralRequest $request, $id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        DB::transaction(function () use ($research, $request)
        {
            $research_output_generals = $research->researchOutputGeneral()->get();
            $path = Storage::url('upload/' . md5($research->propose()->first()->created_by) . '/research-output/generals/');
            if ($request->delete_output !== null)
            {
                foreach ($request->delete_output as $key => $item)
                {
                    if ($item === '1')
                    {
                        $research_output_general = $research_output_generals->get($key);
                        $research_output_general = ResearchOutputGeneral::find($research_output_general->id);
                        if ($research_output_general !== null && $item !== null)
                        {
                            Storage::delete($path . $research_output_general->file_name);
                            $research_output_general->delete();
                        }
                    }
                }
            }

            $key_2 = 0;
            foreach ($request->output_description as $key => $item)
            {
                $research_output_general = $research_output_generals->get($key);
                if ($research_output_general !== null)
                {
                    DB::table('output_members')->where('output_id', $research_output_general->id)->delete();
                }

                if ($request->file_name !== null && array_key_exists($key, $request->file_name))
                {
                    if ($research_output_general !== null && $item !== null)
                    {
                        Storage::delete($path . $research_output_general->file_name);
                    }
                    if ($research_output_general === null)
                    {
                        $research_output_general = new ResearchOutputGeneral();
                    }
                }

                if(!isset($request->status[$key_2]))
                {
                    while(!isset($request->status[$key_2]))
                    {
                        $key_2++;
                        if($key_2 == 100) break;
                    }
                }

                $research_output_general->item = $key + 1;
                $research_output_general->year = $request->year[$key];
                $research_output_general->output_description = $request->output_description[$key];
                $research_output_general->status = $request->status[$key_2];
                $research_output_general->url_address = $request->url_address[$key];
                if ($request->file_name !== null && array_key_exists($key, $request->file_name))
                {
                    $research_output_general->file_name_ori = $request->file('file_name')[$key]->getClientOriginalName();
                    $research_output_general->file_name = md5($request->file('file_name')[$key]->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . $research_output_general->item . '.' . $request->file('file_name')[$key]->extension();
                    $request->file('file_name')[$key]->storeAs($path, $research_output_general->file_name);
                }
                $research_output_general = $research->researchOutputGeneral()->save($research_output_general);

                $output_members = new Collection();
                $ctr_item = 1;
                if (is_array($request->nidn) && array_key_exists($key_2, $request->nidn))
                {
                    foreach ($request->nidn[$key_2] as $nidn_key => $nidn)
                    {
                        $output_member = new OutputMember();
                        $output_member->item = $ctr_item;
                        if (is_array($request->is_external) && array_key_exists($key_2, $request->is_external) &&
                            is_array($request->is_external[$key_2]) && array_key_exists($nidn_key, $request->is_external[$key_2])
                        )
                        {
                            if ($request->is_external[$key_2][$nidn_key] === '1')
                            {
                                $output_member->external = $request->external[$key_2][$nidn_key];
                            }
                        } else
                        {
                            if ($request->nidn[$key_2][$nidn_key] !== null && $request->nidn[$key_2][$nidn_key] !== '')
                            {
                                $output_member->nidn = $request->nidn[$key_2][$nidn_key];
                            }
                        }
                        $output_members->add($output_member);
                        $ctr_item++;
                    }
                }
                $research_output_general->outputMember()->saveMany($output_members);
                $key_2++;
            }
            $this->setOutputFlowStatuses($research);
        });

        return redirect()->intended('researches');
    }

    public function getOutputFile($id)
    {
        $research_output_general = ResearchOutputGeneral::find($id);
        if ($research_output_general === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $propose = $research_output_general->research()->first()->propose()->first();
        $nidn = $propose->created_by;

        $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/research-output/generals/' . $research_output_general->file_name);
        $this->storeDownloadLog($propose->id, 'output services', $research_output_general->file_name_ori, $research_output_general->file_name, $nidn);

        return response()->download($path, $research_output_general->file_name_ori, []);
    }

    public function approveList()
    {
        $periods = Period::all();

        $period = new Period();
        $period->id = '0';
        $period->scheme = 'Mandiri';
        $periods->add($period);

        $period = $periods[0];

        return view('research.research-approve-list', compact(
            'periods',
            'period'
        ));
    }

    public function approveDetail($id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $propose = $research->propose()->first();
        $propose_output_types = $propose->proposeOutputType()->get();

        $status_code = $propose->flowStatus()->orderBy('id', 'desc')->first()->status_code;
        $output_code = $research->outputFlowStatus()->orderBy('id', 'desc')->first()->status_code;

        $research_output_generals = $research->researchOutputGeneral()->get();
        if ($research_output_generals->isEmpty())
        {
            $this->setCSS404();

            return abort('404');
        }
        $output_members = new Collection();
        foreach ($research_output_generals as $key => $research_output_general)
        {
            $output_members[$key] = $research_output_general->outputMember()->get();
            if ($output_members[$key]->isEmpty())
            {
            } else
            {
                foreach ($output_members[$key] as $output_member)
                {
                    if ($output_member->nidn !== null) $output_member->nidn_display = $output_member->nidn . ' : ' . Lecturer::where('employee_card_serial_number', $output_member->nidn)->first()->full_name;
                }
            }
        }

        $research_output_revision = $research->researchOutputRevision()->orderBy('id', 'desc')->first();
        if ($research_output_revision === null || $output_code === 'VL') $research_output_revision = new ResearchOutputRevision();

        $disabled = 'disabled';
        $upd_mode = 'approve';

        return view('research.research-output', compact(
            'research',
            'propose',
            'propose_output_types',
            'output_members',
            'research_output_generals',
            'research_output_revision',
            'upd_mode',
            'status_code',
            'output_code',
            'disabled'
        ));

//        $dedication = Dedication::find($id);
//
//        return view('dedication.dedication-approve-detail');
    }

    public function approveUpdate(Requests\StoreApproveDedicationRequest $request, $id)
    {
        $research = Research::find($id);
        if ($research === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        DB::transaction(function () use ($request, $research)
        {
            $flow_status = $research->propose()->first()->flowStatus()->orderBy('id', 'desc')->first();
            if ($request->is_approved === 'no')
            {
                $research_output_revision = $research->researchOutputRevision()->orderBy('id', 'desc')->first();
                if ($research_output_revision === null)
                {
                    $research_output_revision = new ResearchOutputRevision();
                    $research_output_revision->item = 0;
                }

                $research_output_revision->revision_text = $request->revision_text;
                if ($flow_status->status_code === 'RL')
                {
                    $research_output_revision->updated_by = Auth::user()->nidn;
                    $research_output_revision->save();
                } else
                {
                    $research_output_revision->created_by = Auth::user()->nidn;
                    $research_output_revision->item = $research_output_revision->item + 1;
                    $research->researchOutputRevision()->save($research_output_revision);
                    $research->outputFlowStatus()->create([
                        'item'        => $flow_status->item + 1,
                        'status_code' => 'RL', // Revisi Luaran,
                        'created_by'  => Auth::user()->nidn
                    ]);

                    $this->setEmail('RL', $research->propose()->first());
                }
            } else
            {
                $research->outputFlowStatus()->create([
                    'item'        => $flow_status->item + 1,
                    'status_code' => 'LT', // Validasi Luaran Diterima
                    'created_by'  => Auth::user()->nidn
                ]);
                $this->setEmail('LT', $research->propose()->first());
                if ($flow_status->status_code === 'UL')
                {
                    $research->propose()->first()->flowStatus()->create([
                        'item'        => $flow_status->item + 1,
                        'status_code' => 'PS', // Penelitian Selesai
                        'created_by'  => Auth::user()->nidn
                    ]);
                    $this->setEmail('PS', $research->propose()->first());
                }
            }
        });

        return redirect()->intended($this->deleteUrl . '/approve-list');
    }

    private function setFlowStatuses($research)
    {
        $flow_status = $research->propose()->first()->flowStatus()->orderBy('id', 'desc')->first();
        if ($flow_status->status_code === 'UL' || $flow_status->status_code === 'RL')
        {
            $research->propose()->first()->flowStatus()->create([
                'item'        => $flow_status->item + 1,
                'status_code' => 'VL', //Menunggu Validasi Luaran
                'created_by'  => Auth::user()->nidn,
            ]);
        }
    }

    private function setOutputFlowStatuses($research)
    {
        $output_flow_status = $research->outputFlowStatus()->orderBy('id', 'desc')->first();
        if ($output_flow_status === null)
        {
            $output_flow_status = new OutputFlowStatus();
        }

        $continue_output_flow = true;
        $research_output_generals = $research->researchOutputGeneral()->get();
        if ($research_output_generals->isEmpty())
        {
            $continue_output_flow = false;
        }
        foreach ($research_output_generals as $research_output_general)
        {
            if ($research_output_general->status == 'draft' ||
                $research_output_general->status == 'submitted' ||
                $research_output_general->status == 'accepted'
            )
            {
                $continue_output_flow = false;
                break;
            }
        }

        if ($continue_output_flow || $output_flow_status->status_code === 'RL')
        {
            $research->outputFlowStatus()->create([
                'item'        => $output_flow_status->item + 1,
                'status_code' => 'VL', //Menunggu Validasi Luaran
                'created_by'  => Auth::user()->nidn,
            ]);

            $this->setEmail('VL', $research->propose()->first());
        }
        if ($output_flow_status->status_code === 'LT')
        {
            $flow_status = $research->propose()->first()->flowStatus()->orderBy('id', 'desc')->first();
            $research->propose()->first()->flowStatus()->create([
                'item'        => $flow_status->item + 1,
                'status_code' => 'PS', //Pengabdian Selesai
                'created_by'  => Auth::user()->nidn,
            ]);
            $this->setEmail('PS', $research->propose()->first());
        }
    }

    private function getProposeRelationData($propose = null)
    {
        $ret = new \stdClass();
        $ret->propose_own = $propose->proposesOwn()->first();
        $ret->periods = $propose->period()->get();
        $ret->period = $propose->period()->first();
        $ret->propose_output_types = $propose->proposeOutputType()->get();
        $ret->members = $propose->member()->get();
        $ret->flow_status = $propose->flowStatus()->orderBy('id', 'desc')->first();
        foreach ($ret->members as $member)
        {
            if ($member->external === '1')
            {
                $external_member = $member->externalMember()->first();
                $member->external_name = $external_member->name;
                $member->external_affiliation = $external_member->affiliation;
            } else
            {
                if ($member->nidn !== null && $member->nidn !== '')
                {
                    $member->member_display = $member->nidn . ' : ' . Member::where('id', $member->id)->where('item', $member->item)->first()->lecturer()->first()->full_name;
                    $member->member_nidn = $member->nidn;
                }
            }
        }
        $ret->member = $ret->members->get(0);
        $ret->lecturer = Lecturer::where('employee_card_serial_number', $propose->created_by)->first();
        $ret->faculties = Faculty::where('is_faculty', '1')->get();
        $ret->output_types = Output_type::all();
        $ret->output_types->add(new Output_type());
        $ret->research_types = ResearchType::all();

        if ($ret->propose_own === null)
        {
            $ret->propose_own = new Propose_own();
        }
        if ($ret->periods === null)
        {
            $ret->periods = new Collection();
            $ret->periods->add(new Period);
        }
        if ($ret->period === null)
        {
            $ret->period = new Period();
        }

        return $ret;
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }
}
