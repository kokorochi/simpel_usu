<?php

namespace App\Http\Controllers;

use App\Member;
use App\ModelSDM\Lecturer;
use App\ModelSDM\Employee;
use App\Propose;
use Carbon\Carbon;
use Monolog\Handler\IFTTTHandler;
use View;
use Illuminate\Support\Facades\Auth;

class BlankonController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Blankon Controller
      |--------------------------------------------------------------------------
      |
     */

    // url for asset outside folder laravel
    public $assetUrl;
    // global css
    public $css = [];
    // global js
    public $js = [];
    // body class	
    public $bodyClass = "page-session page-sound page-header-fixed page-sidebar-fixed";
    // sidebar left class	
    public $sidebarClass = "sidebar-circle";

    public $mainTitle = 'LPPM USU - Pengabdian Masyarakat';

    public $v_auths = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->setApp();

        $this->middleware(function ($request, $next)
        {
            if (Auth::user())
            {
                $user_info = Lecturer::where('employee_card_serial_number', Auth::user()->nidn)->first();
                if ($user_info === null) // NIP (Operator)
                {
                    $user_info = Employee::where('number_of_employee_holding', Auth::user()->nidn)->first();
                }
                View::share(compact('user_info'));

                $i = 0;
                $notifications = [];
                $members = Member::where('nidn', Auth::user()->nidn)->where('status', 'waiting')->get();
                foreach ($members as $member)
                {
                    $propose = $member->propose()->first();
                    if ($propose->period()->where('years', '>=', intval(Carbon::now()->toDateString()))->exists())
                    {
                        $notifications[$i]['propose_id'] = $propose->id;
                        $notifications[$i]['propose_title'] = $propose->title;
                        $i++;
                    }
                }
                View::share(compact('notifications'));
            }

            return $next($request);
        });
    }

    /**
     * initialize blankon
     */
    public function setApp()
    {

        $this->assetUrl = config('constant.assetUrl');

        // set global mandatory css
        $this->css = [
            'globals' => ['global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css']
        ];

        // theme styles
        $this->css['themes'] = [
            'admin/css/reset.css',
            'admin/css/layout.css',
            'admin/css/components.css',
            'admin/css/plugins.css',
            'admin/css/themes/laravel.theme.css' => ['id' => ''],
            'admin/css/custom.css',
        ];

        $this->css['pages'] = [];

        $this->js = [
            'cores' => $this->getCoresJs(),
            'ies'   => $this->getIesJs()
        ];

        $this->js['plugins'] = [];

        $this->js['scripts'] = [
            'admin/js/apps.js',
            'admin/js/pages/blankon.dashboard.js',
            'admin/js/demo.js'
        ];

//        dd(Auth::user()->nidn);
//        if(Auth::user()){
//            $user_info = Lecturer::where('employee_card_serial_number', Auth::user()->nidn)->first();
//        }


        // pass variable to view
        View::share('assetUrl', $this->assetUrl);
        View::share('bodyClass', $this->bodyClass);
        View::share('sidebarClass', $this->sidebarClass);
    }

    /**
     * get js core scripts
     * @return array blankon's core javascript plugins
     */
    private function getCoresJs()
    {
        return [
            'global/plugins/bower_components/jquery/dist/jquery.min.js',
            'global/plugins/bower_components/jquery-cookie/jquery.cookie.js',
            'global/plugins/bower_components/bootstrap/dist/js/bootstrap.min.js',
            'global/plugins/bower_components/typehead.js/dist/handlebars.js',
            'global/plugins/bower_components/typehead.js/dist/typeahead.bundle.min.js',
            'global/plugins/bower_components/jquery-nicescroll/jquery.nicescroll.min.js',
            'global/plugins/bower_components/jquery.sparkline.min/index.js',
            'global/plugins/bower_components/jquery-easing-original/jquery.easing.1.3.min.js',
            'global/plugins/bower_components/ionsound/js/ion.sound.min.js',
            'global/plugins/bower_components/bootbox/bootbox.js'
        ];
    }

    /**
     * get Internet Explorer plugin
     * @return array javascript plugins for IE
     */
    private function getIesJs()
    {
        return [
            'global/plugins/bower_components/html5shiv/dist/html5shiv.min.js',
            'global/plugins/bower_components/respond-minmax/dest/respond.min.js'
        ];
    }

}
