<?php

namespace App\Http\Controllers;

use App\Jobs\SendResetPassword;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use View;
use App\Auths;

class LoginController extends BlankonController {

    protected $redirectTo = '/';
    private $pageTitle = 'Sign In';

    public function __construct()
    {
        parent::__construct();

        array_push($this->css['themes'], 'admin/css/pages/sign.css');

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');

        array_push($this->js['scripts'], 'admin/js/pages/blankon.sign.js');

        array_push($this->js['scripts'], 'global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js');

        // pass variable to view
        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', $this->pageTitle . ' | ' . $this->mainTitle);
    }

    public function showLostPassword()
    {
        if (Auth::user())
        {
            return redirect()->intended('/');
        }

        return view('user.lost-password');
    }

    public function doSendPassword(Requests\ForgotPasswordRequest $request)
    {
        $user = User::where('nidn', $request->input['nidn'])->first();
        $lecturer = $user->lecturer()->first();
        $password_reset = PasswordReset::where('nidn', $request->input['nidn'])->first();
        if (is_null($password_reset))
        {
            $password_reset = new PasswordReset();
            $password_reset->nidn = $request->input['nidn'];
            $password_reset->token = md5(bcrypt($password_reset->nidn));
            $password_reset->created_at = Carbon::now()->toDateTimeString();
            $password_reset->save();
        }
        $url = url('/user/reset') . '?nidn=' . $password_reset->nidn . '&token=' . $password_reset->token;

        $recipient = $lecturer->email;
        $email['recipient_name'] = $lecturer->full_name;
        $email['body_content'] = 'Kami informasikan permintaan reset password anda, dapat dilakukan melalui link berikut : <a href="' . $url . '">Reset Password</a>';
        $email['body_detail_content'] = 'Demikian informasi ini kami sampaikan.<br/>Dikirim otomatis oleh Sistem Penlitian USU';
        dispatch(new SendResetPassword($recipient, $email));

        $mask_email = $this->obfuscate_email($lecturer->email);

        $request->session()->flash('alert-success', 'Email telah dikirim ke ' . $mask_email);

        return redirect()->intended('/user/lost');
    }

    public function showLoginForm()
    {
        if (Auth::user())
        {
            return redirect()->intended('/');
        }

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
        }

        return redirect()->intended('/');
    }

    public function reset()
    {
        if (Auth::user())
        {
            View::share('nidn', Auth::user()->nidn);

            return view('user.reset');
        } else
        {
            $input = Input::get();
            if (! empty($input))
            {
                if (isset($input['nidn']) && isset($input['token']))
                {
                    $password_reset = PasswordReset::where('nidn', $input['nidn'])->where('token', $input['token'])->first();
                    if (! is_null($password_reset))
                    {
                        View::share('nidn', $input['nidn']);

                        return view('user.reset');
                    }
                }
            }

            return redirect()->intended('/');
        }
    }

    public function doReset(Requests\StoreResetPasswordRequest $request)
    {
        $user = User::where('nidn', $request->nidn)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        $password_reset = PasswordReset::where('nidn', $request->nidn)->first();
        if (! is_null($password_reset))
        {
            $password_reset->where('nidn', $request->nidn)->delete();
        }

        if (! Auth::user())
        {
            $request->session()->flash('alert-success', 'Password telah direset, silahkan login');
            return redirect()->intended('user/login');
        } else
        {
            return redirect()->intended('/');
        }
    }

    private function obfuscate_email($email)
    {
        $em   = explode("@",$email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);

    }
}