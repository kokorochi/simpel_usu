<?php

namespace App\Http\Controllers;

use App\ResearchType;
use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use Illuminate\Support\Facades\Auth;

class ResearchTypeController extends BlankonController
{
    private $pageTitle = 'Jenis Penelitian';
    protected $deleteQuestion = 'Apakah anda yakin untuk menghapus Jenis Penelitian ini?';
    protected $deleteUrl = 'research-types';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isOperator');
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');

        array_push($this->js['scripts'], 'admin/js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);
        View::share('pageTitle', $this->pageTitle);
        View::share('deleteQuestion', $this->deleteQuestion);
        View::share('deleteUrl', $this->deleteUrl);
    }

    public function index()
    {
        $research_types = ResearchType::paginate(10);

        return view('research-type.research-type-list', compact('research_types'));
    }

    public function create()
    {
        $research_type = new ResearchType();
        $upd_mode = 'create';

        return view('research-type.research-type-detail', compact('research_type', 'upd_mode'));
    }

    public function store(Requests\StoreResearchTypeRequest $request)
    {
        $research_types = new ResearchType();
        $research_types->research_name = $request->research_name;
        $research_types->created_by = Auth::user()->nidn;
        $research_types->save();

        return redirect()->intended($this->deleteUrl);
    }

    public function edit($id)
    {
        $research_type = ResearchType::find($id);
        if ($research_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $upd_mode = 'edit';

        return view('research-type.research-type-detail', compact('research_type', 'upd_mode'));
    }

    public function update(Requests\StoreResearchTypeRequest $request, $id)
    {
        $research_type = ResearchType::find($id);
        if ($research_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $research_type->research_name = $request->research_name;
        $research_type->updated_by = Auth::user()->nidn;
        $research_type->save();

        return redirect()->intended($this->deleteUrl);
    }

    public function destroy($id)
    {
        $research_type = ResearchType::find($id);
        if ($research_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $research_type->delete();

        return redirect()->intended($this->deleteUrl);
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }
}
