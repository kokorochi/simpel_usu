<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use Monolog\Handler\IFTTTHandler;
use View;
use App\Auths;

class LoginController extends BlankonController {

    protected $redirectTo = '/';
    private $pageTitle = 'Sign In';

    public function __construct()
    {
        parent::__construct();
    }

    public function showLoginForm()
    {
        if (Auth::user())
        {
            return redirect()->intended('/');
        }
        array_push($this->css['themes'], 'admin/css/pages/sign.css');

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');

        array_push($this->js['scripts'], 'admin/js/pages/blankon.sign.js');

        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');

        // pass variable to view
        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);

        return view('user.signin');
    }

    public function doLogin(Requests\LoginRequest $request)
    {
        Auth::user()->userAuths = Auths::find(Auth::user()->id);

        return redirect()->intended('/');
    }

    public function doLogout()
    {
        if (Auth::user())
        {
            unset($this->v_auths);
            Auth::logout();

            return redirect()->intended('/');
        }
    }
}