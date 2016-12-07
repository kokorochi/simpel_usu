<?php

namespace App\Http\Controllers;

use App\Appraisal_i;
use App\Conclusion;
use App\Dedication_reviewer;
use App\ModelSDM\Lecturer;
use App\Propose;
use App\ReviewPropose;
use App\ReviewProposesI;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use View;

class ReviewProposeController extends BlankonController {
    protected $pageTitle = 'Review Proposal';
    protected $deleteQuestion = 'Apakah anda yakin untuk menghapus Pengajuan Proposal ini?';
    protected $deleteUrl = 'review-proposes';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isReviewer');
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');

        array_push($this->js['scripts'], 'admin/js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('pageTitle', $this->pageTitle);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);
        View::share('deleteUrl', $this->deleteUrl);
        View::share('deleteQuestion', $this->deleteQuestion);
    }

    public function index()
    {
        $proposes = new Collection;
        $dedication_reviewers = Dedication_reviewer::where('nidn', Auth::user()->nidn)->get();
        foreach ($dedication_reviewers as $dedication_reviewer)
        {
            $propose = $dedication_reviewer->propose()->first();
            $period = $propose->period()->first();
            if ($period->review_begda <= Carbon::now()->toDateString() &&
                $period->review_endda >= Carbon::now()->toDateString()
            )
            {
                if ($propose->flowStatus()->orderBy('item', 'desc')->first()->status_code === 'MR' ||
                    $propose->flowStatus()->orderBy('item', 'desc')->first()->status_code === 'RS')
                {
                    $proposes->add($propose);
                }
            }
        }

        $data_not_found = 'Tidak ada proposal untuk di-review';

        return view('review-propose.review-propose-list', compact(
            'proposes',
            'data_not_found'
        ));
    }

    public function review($id)
    {
        $propose = Propose::find($id);
        if ($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $upd_mode = 'create';

        $review_propose = ReviewPropose::where('propose_id', $id)->where('nidn', Auth::user()->nidn)->first();
        if ($review_propose === null)
        {
            $review_propose = new ReviewPropose;
            $review_propose->disabled = '';
            $appraisal = $propose->period()->first()->appraisal()->first();
            $appraisals_i = $appraisal->appraisal_i()->get();
            $review_proposes_i = new Collection;
            foreach ($appraisals_i as $item)
            {
                $review_propose_i = new ReviewProposesI;
                $review_propose_i->item = $item->item;
                $review_propose_i->aspect = $item->aspect;
                $review_propose_i->quality = $item->quality;
                $review_propose_i->disabled = '';
                $review_proposes_i->add($review_propose_i);
            }

        } else
        {
            $review_proposes_i = $review_propose->reviewProposesI()->get();
            foreach ($review_proposes_i as $review_propose_i)
            {
                $review_propose_i->disabled = 'disabled';
            }
            $review_propose->disabled = 'disabled';
            $upd_mode = 'display';
        }
        $conclusions = Conclusion::all();

        return view('review-propose.review-propose-detail', compact(
            'propose',
            'review_propose',
            'review_proposes_i',
            'conclusions',
            'upd_mode'
        ));
    }

    public function reviewUpdate(Requests\StoreReviewUpdateRequest $request, $id)
    {
        $propose = Propose::find($id);
        if ($propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $appraisal = $propose->period()->first()->appraisal()->first();
        $appraisals_i = $appraisal->appraisal_i()->get();

        $review_propose = new ReviewPropose();
        $review_propose->propose_id = $id;
        $review_propose->nidn = Auth::user()->nidn;
        $review_propose->suggestion = $request->suggestion;
        $review_propose->conclusion_id = $request->conclusion_id;

        $review_proposes_i = new Collection();
        foreach ($appraisals_i as $key => $appraisal_i)
        {
            $review_propose_i = new ReviewProposesI();
            $review_propose_i->item = $appraisal_i->item;
            $review_propose_i->aspect = $appraisal_i->aspect;
            $review_propose_i->quality = $appraisal_i->quality;
            $review_propose_i->score = $request->score[$key];
            $review_propose_i->comment = $request->comment[$key];
            $review_proposes_i->add($review_propose_i);
        }

        $ctr_dedication_reviewers = count($propose->dedicationReviewer()->get());
        $ctr_review_proposes = count(ReviewPropose::where('propose_id', $id)->get());
        $count = $ctr_dedication_reviewers - $ctr_review_proposes;

        DB::transaction(function () use ($review_propose, $review_proposes_i, $count, $propose)
        {
            $review_propose->save();
            foreach ($review_proposes_i as $item)
            {
                $review_propose->reviewProposesI()->save($item);
            }
            if ($count === 1)
            {
                $flow_status = $propose->flowStatus()->orderBy('item', 'desc')->first();
                $propose->flowStatus()->create([
                    'item'        => $flow_status->item + 1,
                    'status_code' => 'RS', //Review Selesai, menunggu hasil
                    'created_by'  => Auth::user()->nidn,
                ]);
            }
        });

        return redirect()->intended('review-proposes');
    }

    public function printReview($id)
    {
        $review_propose = ReviewPropose::find($id);
        if ($review_propose === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $review_proposes_i = $review_propose->reviewProposesI()->get();
        $month = date('M', strtotime(Carbon::now()->toDateString()));
        switch ($month)
        {
            case 'Jan':
                $month = 'Januari';
                break;
            case 'Feb':
                $month = 'Pebruari';
                break;
            case 'Mar':
                $month = 'Maret';
                break;
            case 'Apr':
                $month = 'April';
                break;
            case 'May':
                $month = 'Mei';
                break;
            case 'Jun':
                $month = 'Juni';
                break;
            case 'Jul':
                $month = 'Juli';
                break;
            case 'Aug':
                $month = 'Agustus';
                break;
            case 'Sep':
                $month = 'September';
                break;
            case 'Oct':
                $month = 'Oktober';
                break;
            case 'Nov':
                $month = 'Nopember';
                break;
            case 'Dec':
                $month = 'Desember';
                break;
        }
        $today_date = date('d', strtotime(Carbon::now()->toDateString())) . ' ' .
            $month . ' ' . date('Y', strtotime(Carbon::now()->toDateString()));
        
        $lead = $review_propose->propose()->first()->created_by;
        $lead = Lecturer::where('employee_card_serial_number', $lead)->first();
        if (! ($lead->front_degree === null || $lead->front_degree === '' || $lead->front_degree === '-'))
        {
            $lead->full_name = $lead->front_degree . ' ' . $lead->full_name;
        }
        if (! ($lead->behind_degree === null || $lead->behind_degree === '' || $lead->behind_degree === '-'))
        {
            $lead->full_name = $lead->full_name . ', ' . $lead->behind_degree;
        }

        $reviewer = Lecturer::where('employee_card_serial_number', Auth::user()->nidn)->first();
        if (! ($reviewer->front_degree === null || $reviewer->front_degree === '' || $reviewer->front_degree === '-'))
        {
            $reviewer->full_name = $reviewer->front_degree . ' ' . $reviewer->full_name;
        }
        if (! ($reviewer->behind_degree === null || $reviewer->behind_degree === '' || $reviewer->behind_degree === '-'))
        {
            $reviewer->full_name = $reviewer->full_name . ', ' . $reviewer->behind_degree;
        }

        return view('review-propose.review-propose-print', compact(
            'review_propose',
            'review_proposes_i',
            'today_date',
            'lead',
            'reviewer'
        ));
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }

}
