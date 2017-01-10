<?php

namespace App\Http\Controllers;

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
        $this->middleware('isLecturer')->except(['approveList', 'approveDetail', 'approveUpdate']);
        $this->middleware('isOperator')->only(['approveList', 'approveDetail', 'approveUpdate']);
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
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/jquery-ui.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/ui/minified/autocomplete.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/dataTables.bootstrap.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/datatables.responsive.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js');

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
        $researches = new Collection();
        foreach ($proposes as $propose)
        {
            $research = $propose->research()->first();
            if ($research !== null) $researches->add($research);
        }
        $data_not_found = 'Data tidak ditemukan';

        return view('research.research-list', compact(
            'researches',
            'data_not_found'
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

        $disabled = 'disabled';
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

        $flow_status = $research->propose()->first()->flowStatus()->orderBy('item', 'desc')->first();
        if ($flow_status->status_code === 'UD')
        {
            $research->propose()->first()->flowStatus()->create([
                'item'        => $flow_status->item + 1,
                'status_code' => 'LK', //Menunggu Laporan Kemajuan
                'created_by'  => Auth::user()->nidn,
            ]);
            $flow_status->status_code = 'LK';
        }

        $propose_output_types = $propose->proposeOutputType()->get();
        $propose_own = $propose->proposesOwn()->first();
        $periods = $propose->period()->get();
        $period = $propose->period()->first();
        $members = $propose->member()->get();
        foreach ($members as $member)
        {
            if ($member->external === '1')
            {
                $external_member = $member->externalMember()->first();
                $member->external_name = $external_member->name;
                $member->external_affiliation = $external_member->affiliation;
            } else
            {
                $member['member_display'] = Member::where('id', $member->id)->where('item', $member->item)->first()->lecturer()->first()->full_name;
            }
        }
        $member = $propose->member()->first();
        $lecturer = Lecturer::where('employee_card_serial_number', $propose->created_by)->first();
        $faculties = Faculty::where('is_faculty', '1')->get();
        $output_types = Output_type::all();
        $research_types = ResearchType::all();

        if ($propose_own === null)
        {
            $propose_own = new Propose_own();
        }
        if ($periods === null)
        {
            $periods = new Collection();
            $periods->add(new Period);
        }
        if ($period === null)
        {
            $period = new Period();
        }

        $disable_upload = false;
        $status_code = $propose->flowStatus()->orderBy('item', 'desc')->first()->status_code;
        if ($status_code !== 'UU' && $status_code !== 'PR')
        {
            $disable_upload = true;
        }

        $disable_final_amount = 'readonly';
        $upd_mode = 'edit';

        return view('research.research-edit', compact(
            'research',
            'propose',
            'propose_own',
            'propose_output_types',
            'periods',
            'period',
            'output_types',
            'research_types',
            'faculties',
            'disable_upload',
            'members',
            'member',
            'lecturer',
            'status_code',
            'disable_final_amount',
            'disabled',
            'upd_mode',
            'flow_status'
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

            $flow_status = $research->propose()->first()->flowStatus()->orderBy('item', 'desc')->first();
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

            $research->file_final_activity_ori = $request->file('file_final_activity')->getClientOriginalName();
            $research->file_final_activity = md5($request->file('file_final_activity')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->file_final_budgets_ori = $request->file('file_final_budgets')->getClientOriginalName();
            $research->file_final_budgets = md5($request->file('file_final_budgets')->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.pdf';

            $research->updated_by = Auth::user()->nidn;
            $research->save();

            $request->file('file_final_activity')->storeAs($path, $research->file_final_activity);
            $request->file('file_final_budgets')->storeAs($path, $research->file_final_budgets);

            $flow_status = $research->propose()->first()->flowStatus()->orderBy('item', 'desc')->first();
            if ($flow_status->status_code === 'LA')
            {
                $research->propose()->first()->flowStatus()->create([
                    'item'        => $flow_status->item + 1,
                    'status_code' => 'UL', //Menunggu Luaran
                    'created_by'  => Auth::user()->nidn,
                ]);
            }

        });

        return redirect()->intended($this->deleteUrl);
    }

    public function getFile($id, $type)
    {
//        dd($id . ' ' . $type);
        $research = Research::find($id);
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

        $status_code = $propose->flowStatus()->orderBy('item', 'desc')->first()->status_code;

        $research_output_generals = $research->researchOutputGeneral()->get();
        if ($research_output_generals->isEmpty())
        {
            $research_output_generals = new Collection();
            for ($i = 0; $i < 2; $i++)
            {
                $research_output_general = new ResearchOutputGeneral();
                $research_output_generals->add($research_output_general);
            }
        }

        $research_output_revision = $research->researchOutputRevision()->orderBy('item', 'desc')->first();
        if ($research_output_revision === null) $research_output_revision = new ResearchOutputRevision();

        $disabled = '';
        if ($status_code === 'PS') $disabled = 'disabled';
        $upd_mode = 'output';

        return view('research.research-output', compact(
            'research',
            'propose',
            'propose_output_types',
            'status_code',
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
                        if ($research_output_general !== null && $item !== null)
                        {
                            Storage::delete($path . $research_output_general->file_name);
                            $research_output_general->delete();
                        }
                    }
                }
            }

            if ($request->file_name !== null)
            {
                foreach ($request->file_name as $key => $item)
                {
                    $research_output_general = $research_output_generals->get($key);
                    if ($research_output_general !== null && $item !== null)
                    {
                        Storage::delete($path . $research_output_general->file_name);
                    }
                    if ($research_output_general === null)
                    {
                        $research_output_general = new ResearchOutputGeneral();
                    }
                    $research_output_general->item = $key + 1;
                    $research_output_general->output_description = $request->output_description[$key];
                    $research_output_general->file_name_ori = $request->file('file_name')[$key]->getClientOriginalName();
                    $research_output_general->file_name = md5($request->file('file_name')[$key]->getClientOriginalName() . Carbon::now()->toDateTimeString()) . $research->id . '.' . $request->file('file_name')[$key]->extension();
                    $research->researchOutputGeneral()->save($research_output_general);

                    $request->file('file_name')[$key]->storeAs($path, $research_output_general->file_name);
                }
            }
            $this->setFlowStatuses($research);
        });

        return redirect()->intended('researches');
    }

    public function getOutputFile($id, $type, $subtype = 0)
    {
        if ($type == 1) //Service
        {
            $research_output_general = DedicationOutputService::find($id);
            if ($research_output_general === null)
            {
                $this->setCSS404();

                return abort('404');
            }
            $propose = $research_output_general->dedication()->first()->propose()->first();
            $nidn = $propose->created_by;
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/dedication-output/services/' . $research_output_general->file_name);

            $this->storeDownloadLog($propose->id, 'output services', $research_output_general->file_name_ori, $research_output_general->file_name, $nidn);

            return response()->download($path, $research_output_general->file_name_ori, ['Content-Type' => 'images/jpeg']);
        } elseif ($type == 2) //Method
        {
            $dedication_output_method = DedicationOutputMethod::find($id);
            if ($dedication_output_method === null)
            {
                $this->setCSS404();

                return abort('404');
            }
            $propose = $dedication_output_method->dedication()->first()->propose()->first();
            $nidn = $propose->created_by;
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/dedication-output/methods/' . $dedication_output_method->file_name);

            $this->storeDownloadLog($propose->id, 'output methods', $dedication_output_method->file_name_ori, $dedication_output_method->file_name, $nidn);

            return response()->download($path, $dedication_output_method->file_name_ori, []);
        } elseif ($type == 3) //Product
        {
            $dedication_output_product = DedicationOutputProduct::find($id);
            if ($dedication_output_product === null)
            {
                $this->setCSS404();

                return abort('404');
            }
            $propose = $dedication_output_product->dedication()->first()->propose()->first();
            $nidn = $propose->created_by;
            if ($subtype == 1)
            {
                $file_ori = $dedication_output_product->file_blueprint_ori;
                $file = $dedication_output_product->file_blueprint;
                $this->storeDownloadLog($propose->id, 'output products blueprint', $dedication_output_product->file_blueprint_ori, $dedication_output_product->file_blueprint, $nidn);
            } elseif ($subtype == 2)
            {
                $file_ori = $dedication_output_product->file_finished_good_ori;
                $file = $dedication_output_product->file_finished_good;
                $this->storeDownloadLog($propose->id, 'output products FG', $dedication_output_product->file_finished_good_ori, $dedication_output_product->file_finished_good, $nidn);
            } elseif ($subtype == 3)
            {
                $file_ori = $dedication_output_product->file_working_pic_ori;
                $file = $dedication_output_product->file_working_pic;
                $this->storeDownloadLog($propose->id, 'output products WP', $dedication_output_product->file_working_pic_ori, $dedication_output_product->file_working_pic, $nidn);
            }

            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/dedication-output/products/' . $file);

            return response()->download($path, $file_ori, []);
        } elseif ($type == 4) //Patent
        {
            $dedication_output_patent = DedicationOutputPatent::find($id);
            if ($dedication_output_patent === null)
            {
                $this->setCSS404();

                return abort('404');
            }
            $propose = $dedication_output_patent->dedication()->first()->propose()->first();
            $nidn = $propose->created_by;
            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/dedication-output/patents/' . $dedication_output_patent->file_patent);

            $this->storeDownloadLog($propose->id, 'output patents', $dedication_output_patent->file_patent_ori, $dedication_output_patent->file_patent, $nidn);

            return response()->download($path, $dedication_output_patent->file_patent_ori, []);
        } elseif ($type == 5) //Guidebook
        {
            $dedication_output_guidebook = DedicationOutputGuidebook::find($id);
            if ($dedication_output_guidebook === null)
            {
                $this->setCSS404();

                return abort('404');
            }
            $propose = $dedication_output_guidebook->dedication()->first()->propose()->first();
            $nidn = $propose->created_by;
            if ($subtype == 1)
            {
                $file_ori = $dedication_output_guidebook->file_cover_ori;
                $file = $dedication_output_guidebook->file_cover;
                $this->storeDownloadLog($propose->id, 'output guidebooks', $dedication_output_guidebook->file_cover_ori, $dedication_output_guidebook->file_cover, $nidn);
            } elseif ($subtype == 2)
            {
                $file_ori = $dedication_output_guidebook->file_back_ori;
                $file = $dedication_output_guidebook->file_back;
                $this->storeDownloadLog($propose->id, 'output guidebooks', $dedication_output_guidebook->file_back_ori, $dedication_output_guidebook->file_back, $nidn);
            } elseif ($subtype == 3)
            {
                $file_ori = $dedication_output_guidebook->file_table_of_contents_ori;
                $file = $dedication_output_guidebook->file_table_of_contents;
                $this->storeDownloadLog($propose->id, 'output guidebooks', $dedication_output_guidebook->file_table_of_contents_ori, $dedication_output_guidebook->file_table_of_contents, $nidn);
            }

            $path = storage_path() . '/app' . Storage::url('upload/' . md5($nidn) . '/dedication-output/guidebooks/' . $file);

            return response()->download($path, $file_ori, []);
        }
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

        $status_code = $propose->flowStatus()->orderBy('item', 'desc')->first()->status_code;

        $research_output_generals = $research->researchOutputGeneral()->get();
        if ($research_output_generals->isEmpty())
        {
            $this->setCSS404();

            return abort('404');
        }

        $research_output_revision = $research->researchOutputRevision()->orderBy('item', 'desc')->first();
        if ($research_output_revision === null) $research_output_revision = new ResearchOutputRevision();

        $disabled = 'disabled';
        $upd_mode = 'approve';

        return view('research.research-output', compact(
            'research',
            'propose',
            'propose_output_types',
            'research_output_generals',
            'research_output_revision',
            'upd_mode',
            'status_code',
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
            $flow_status = $research->propose()->first()->flowStatus()->orderBy('item', 'desc')->first();
            if ($request->is_approved === 'no')
            {
                $research_output_revision = $research->researchOutputRevision()->orderBy('item', 'desc')->first();
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
                    $research->propose()->first()->flowStatus()->create([
                        'item'        => $flow_status->item + 1,
                        'status_code' => 'RL', // Revisi Luaran,
                        'created_by'  => Auth::user()->nidn
                    ]);
                }
            } else
            {
                $research->propose()->first()->flowStatus()->create([
                    'item'        => $flow_status->item + 1,
                    'status_code' => 'PS', // Penelitian Selesai,
                    'created_by'  => Auth::user()->nidn
                ]);
            }
        });

        return redirect()->intended($this->deleteUrl . '/approve-list');
    }

    private function setFlowStatuses($research)
    {
        $flow_status = $research->propose()->first()->flowStatus()->orderBy('item', 'desc')->first();
        if ($flow_status->status_code === 'UL' || $flow_status->status_code === 'RL')
        {
            $research->propose()->first()->flowStatus()->create([
                'item'        => $flow_status->item + 1,
                'status_code' => 'VL', //Menunggu Validasi Luaran
                'created_by'  => Auth::user()->nidn,
            ]);
        }
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }
}
