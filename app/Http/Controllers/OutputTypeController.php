<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutputTypeRequest;
use App\Output_type;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;

class OutputTypeController extends BlankonController
{
    private $pageTitle = 'Luaran';
    protected $deleteQuestion = 'Apakah anda yakin untuk menghapus Luaran ini?';
    protected $deleteUrl = 'output-types';

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
        $output_types = Output_type::paginate(10);

        return view('output-type.output-type-list', compact('output_types'));
    }

    public function create()
    {
        $output_type = new Output_type();
        $upd_mode = 'create';

        return view('output-type.output-type-detail', compact('output_type', 'upd_mode'));
    }

    public function store(StoreOutputTypeRequest $request)
    {
        $output_type = new Output_type();
        $output_type->fill($request->input);
        $output_type->created_by = Auth::user()->nidn;
        $output_type->save();

        return redirect()->intended($this->deleteUrl);
    }

    public function edit($id)
    {
        $output_type = Output_type::find($id);
        if ($output_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $upd_mode = 'edit';

        return view('output-type.output-type-detail', compact('output_type', 'upd_mode'));
    }

    public function update(StoreOutputTypeRequest $request, $id)
    {
        $output_type = Output_type::find($id);
        if ($output_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }

        $output_type->fill($request->input);
        $output_type->updated_by = Auth::user()->nidn;
        $output_type->save();

        return redirect()->intended($this->deleteUrl);
    }

    public function destroy($id)
    {
        $output_type = Output_type::find($id);
        if ($output_type === null)
        {
            $this->setCSS404();

            return abort('404');
        }
        $output_type->delete();

        return redirect()->intended($this->deleteUrl);
    }

    private function setCSS404()
    {
        array_push($this->css['themes'], 'admin/css/pages/error-page.css');
        View::share('css', $this->css);
    }
}
