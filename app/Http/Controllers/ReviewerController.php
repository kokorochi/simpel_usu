<?php

namespace App\Http\Controllers;

use App\Auths;
use App\ResearchReviewer;
use App\ResearchType;
use App\FlowStatus;
use App\Member;
use App\ModelSDM\Faculty;
use App\ModelSDM\Lecturer;
use App\Output_type;
use App\Period;
use App\Propose;
use App\Propose_own;
use App\ReviewPropose;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use View;

class ReviewerController extends BlankonController {
    protected $pageTitle = 'Reviewer';
    protected $deleteQuestion = 'Apakah anda yakin untuk menghapus Pengajuan Proposal ini?';
    protected $deleteUrl = 'reviewers';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isOperator');
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jquery-ui/themes/base/jquery-ui.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/css/datepicker.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/css/dataTables.bootstrap.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/datatables/css/datatables.responsive.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/fuelux/dist/css/fuelux.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.jquery.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/jquery-ui.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-ui/ui/minified/autocomplete.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/jquery.dataTables.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/dataTables.bootstrap.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/datatables/js/datatables.responsive.js');
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
        $auths = Auths::where('auth_object_ref_id', '3')->paginate(10);
        $data_not_found = 'Tidak ada reviewer';
        foreach ($auths as $auth)
        {
            $auth->begin_date = date('d-M-Y', strtotime($auth->begin_date));
            $auth->end_date = date('d-M-Y', strtotime($auth->end_date));
        }

        return view('reviewer/reviewer-list', compact(
            'auths',
            'data_not_found'
        ));
    }

    public function create()
    {
        $auths = new Auths;
        $lecturer = new Lecturer;
        $upd_mode = 'create';

        return view('reviewer/reviewer-create', compact('auths', 'upd_mode', 'lecturer'));
    }

    public function store(Requests\StoreReviewer $request)
    {
        $user = User::where('nidn', $request->nidn)->first();

        $user->auths()->create([
            'auth_object_ref_id' => '3',
            'begin_date'         => date('Y-m-d', strtotime($request->begin_date)),
            'end_date'           => date('Y-m-d', strtotime($request->end_date)),
            'created_by'         => Auth::user()->nidn,
        ]);

        return redirect()->intended('reviewers/');
    }

    public function edit($id)
    {
        $auths = Auths::find($id);
        if ($auths === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $upd_mode = 'edit';
        $lecturer = $auths->user()->first()->lecturer()->first();
        $auths->nidn = $auths->user()->first()->nidn;
        $auths->full_name = $lecturer->full_name;
        $auths->begin_date = date('d-m-Y', strtotime($auths->begin_date));
        $auths->end_date = date('d-m-Y', strtotime($auths->end_date));

        return view('reviewer/reviewer-edit', compact(
            'auths',
            'upd_mode',
            'lecturer'
        ));
    }

    public function update(Requests\StoreReviewer $request, $id)
    {
        $auth = Auths::find($id);
        $auth->begin_date = date('Y-m-d', strtotime($request->begin_date));
        $auth->end_date = date('Y-m-d', strtotime($request->end_date));
        $auth->updated_by = Auth::user()->nidn;
        $auth->save();

        return redirect()->intended('reviewers/');
    }

    public function assignList()
    {
        $periods = Period::where('review_endda', '>=', Carbon::now()->toDateString())->get();
        if($periods->isEmpty())
        {
            $periods->add(new Period);
        }
        $period = $periods[0];
        $proposes = $period->propose()->paginate(10);
        $data_not_found = 'Tidak ada pengajuan proposal untuk scheme ini';

        return view('reviewer/reviewer-assign-list', compact(
            'periods',
            'period',
            'proposes',
            'data_not_found'
        ));
    }

    public function assign($id)
    {
        $propose = Propose::find($id);
        if($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $propose_relation = $this->getProposeRelationData($propose);
        $propose_relation->propose = $propose;
        $research_reviewers = ResearchReviewer::where('propose_id', $propose->id)->get();
        $research_reviewer = new ResearchReviewer();
        if ($research_reviewers->isEmpty())
        {
            $research_reviewers = new Collection;
            $research_reviewers->add($research_reviewer);
        } else
        {
            foreach ($research_reviewers as $research_reviewer)
            {
                $research_reviewer->display = Lecturer::where('employee_card_serial_number', $research_reviewer->nidn)->first()->full_name;
                $research_reviewer->disabled = 'readonly';
            }
        }

        $reviewers = Auths::where('auth_object_ref_id', '3')->get();
        foreach ($reviewers as $reviewer)
        {
            $lecturer = $reviewer->user()->first()->lecturer()->first();
            $reviewer->nidn = $lecturer->employee_card_serial_number;
            $reviewer->full_name = $reviewer->nidn . ' : ' . $lecturer->full_name;
        }

        $disabled = 'disabled';
        $disable_upload = true;
        if($propose_relation->flow_status->status_code === 'PR' ||
           $propose_relation->flow_status->status_code === 'MR')
        {
            $disable_reviewer = false;
        }else{
            $disable_reviewer = true;
        }

        return view('reviewer/reviewer-assign', compact(
            'propose_relation',
            'research_reviewers',
            'reviewers',
            'disabled',
            'disable_upload',
            'disable_reviewer'
        ));
    }

    public function assignUpdate(Requests\StoreAssignReviewerRequest $request, $id)
    {
        $propose = Propose::find($id);
        if ($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $research_reviewers = $propose->researchReviewer()->withTrashed()->get();
        $item_no = 1;
        if ($research_reviewers->isEmpty() === false)
        {
            $item_no = $research_reviewers->sortByDesc('item')->first()->item;
        }

        $research_review_restore = new Collection;
        $research_review_new = new Collection;
        $research_review_delete = new Collection;
        foreach ($request->nidn as $item)
        {   
            $research_review = $research_reviewers->where('nidn', $item)->first();
            if ($research_review !== null)
            {
                $research_review_restore->add($research_review);
            } else
            {
                $research_review = new ResearchReviewer();
                $research_review->propose_id = $id;
                $research_review->nidn = $item;
                $research_review->item = $item_no++;
                $research_review_new->add($research_review);
            }
        }

        foreach ($research_reviewers as $research_reviewer)
        {
            if ($research_reviewer->deleted_at === null)
            {
                $pos = array_search($research_reviewer->nidn, $request->nidn);
                if ($pos === false)
                {
                    $research_review_delete->add($research_reviewer);
                }
            }
        }

        $flow_statuses = $propose->flowStatus()->orderBy('item', 'desc')->first();
        $flow_statuses->item = $flow_statuses->item + 1;
        $flow_statuses->status_code = 'MR'; //Menunggu diReview
        $flow_statuses->created_by = Auth::user()->nidn;

        DB::transaction(function() use($research_review_new, $research_review_restore, $research_review_delete, $flow_statuses, $propose){
            foreach ($research_review_restore as $item)
            {
                $item->restore();
                $item->updated_by = Auth::user()->nidn;
                $item->save();

                $this->setEmail('reviewer new', $propose, $item->nidn);
            }
            foreach ($research_review_new as $item)
            {
                $item->created_by = Auth::user()->nidn;
                $item->save();

                $this->setEmail('reviewer new', $propose, $item->nidn);
            }
            foreach ($research_review_delete as $item)
            {
                $item->updated_by = Auth::user()->nidn;
                $item->save();
                $item->delete();

                $review_propose = $propose->reviewPropose()->where('nidn', $item->nidn)->first();
                if($review_propose !== null) $review_propose->delete();

                $this->setEmail('reviewer delete', $propose, $item->nidn);
            }
            $flow_statuses->save();
        });
        return redirect()->intended('reviewers/assign');
    }

    public function destroy($id)
    {
        $auth = Auths::find($id);
        if(is_null($auth))
        {
            $this->setCSS404();

            return abort('404');
        }

        $auth->delete();

        return redirect()->intended('reviewers');
    }

    private function getProposeRelationData($propose = null)
    {
        $ret = new \stdClass();
        if ($propose !== null)
        {
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
        } else
        {
            $ret->propose = new Propose();
            $ret->propose_own = new Propose_own();

            $ret->periods = Period::where('propose_begda', '<=', Carbon::now()->toDateString())->where('propose_endda', '>=', Carbon::now()->toDateString())->get();
            if ($ret->periods->isEmpty())
            {
                $ret->period = new Period();
                $ret->propose->is_own = '1';
            } else
            {
                $ret->period = $ret->periods->get(0);
            }
            $ret->output_types = Output_type::all();
            $ret->output_types->add(new Output_type());
            $ret->category_types = Category_type::all();
            $ret->research_types = ResearchType::all();
            $ret->propose_output_types = new Collection();
            $ret->propose_output_types->add(new ProposeOutputType());
            $ret->propose_output_types->add(new ProposeOutputType());
            $ret->propose_output_types->add(new ProposeOutputType());

            $ret->lecturer = $this->getEmployee(Auth::user()->nidn);

            $ret->members = new Collection;
            $ret->member = new Member;
            $ret->members->add(new Member);

            $ret->faculties = Faculty::where('is_faculty', '1')->where('faculty_code', '<>', 'SPS')->get();
        }

        return $ret;
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }
}
