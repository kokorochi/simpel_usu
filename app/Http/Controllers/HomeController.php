<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Announce;
use View;

class HomeController extends BlankonController
{
    public function __construct()
    {
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/dropzone/downloads/css/dropzone.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jquery.gritter/css/jquery.gritter.css');

//        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.spline.min.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.categories.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.tooltip.min.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.resize.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/flot/jquery.flot.pie.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/dropzone/downloads/dropzone.min.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery.gritter/js/jquery.gritter.min.js');
//        array_push($this->js['plugins'], 'global/plugins/bower_components/skycons-html5/skycons.js');

        array_push($this->js['scripts'], 'admin/js/customize.js');

        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', $this->mainTitle);
    }

    public function index()
    {
        $announces = Announce::orderBy('updated_at', 'DESC')->paginate(5);
        foreach ($announces as $announce) {
            $announce->content = strip_tags($announce->content);
            $announce->content = substr($announce->content, 0, 500);
        }
        return view('home/index', compact('announces'));
    }
}
