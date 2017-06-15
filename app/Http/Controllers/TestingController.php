<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\ModelSDM\Lecturer;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Excel;
use Symfony\Component\HttpFoundation\File\File;
use View;
use Illuminate\Support\Facades\Storage;

class TestingController extends BlankonController {
    public function __construct()
    {
        parent::__construct();

        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/summernote/dist/summernote.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/animate.css/animate.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css');
        array_push($this->css['pages'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.min.css');

        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/holderjs/holder.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/bootstrap-maxlength/bootstrap-maxlength.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/jquery-autosize/jquery.autosize.min.js');
        array_push($this->js['plugins'], 'global/plugins/bower_components/chosen_v1.2.0/chosen.jquery.min.js');

        array_push($this->js['scripts'], 'admin/js/pages/blankon.form.wysiwyg.js');
//        array_push($this->js['scripts'], 'code.jquery.com/jquery-1.11.2.min.js');
        array_push($this->js['scripts'], 'admin/js/customize.js');
        array_push($this->js['scripts'], 'admin/js/pages/blankon.form.element.js');
    }

    public function index()
    {
//        return (public_path('images'));
//        $path = public_path('images\upload\testing');
//        $visibility = Storage::getVisibility('images/1.jpg');

//        return $visibility;
//        $file = File::
//        response()->download($file);

//        return response()::file('1.jpg');

//        return response()->download($path, '1.jpg', ['Content-Type' => 'image/jpg']);
    }

    public function upload()
    {
        View::share('css', $this->css);
        View::share('js', $this->js);
        View::share('title', 'Upload Files');
        View::share('assetUrl', $this->assetUrl);

        return view('testing.file-upload');
    }

    public function storeUpload(Request $request)
    {
//        $imageName = '1' . '.' .
//            $request->file('image')->getClientOriginalExtension();
//
//        $request->file('image')->move(
//            public_path('images\upload\testing') , $imageName
//        );

//        $path = $request->file('image')->storeAs('images', $imageName);

        $visibility = Storage::getVisibility('images/1.jpg');

        $path = Storage::url('images/');

        $path = storage_path() . '/app/images/1.jpg';
//        return $path;

        $content = Storage::get('images/1.jpg');

//        return $content;

        return response()->download($path, '1.jpg', ['Content-Type' => 'image/jpg']);

    }

    public function initiateLecturer()
    {
        $lecturers = Lecturer::all();
        $clone_lecturers = new Collection();
        foreach ($lecturers as $lecturer)
        {
            if ($lecturer->employee_card_serial_number !== null && $lecturer->employee_card_serial_number !== '')
            {
                $clone_lecturer = new User();
                $clone_lecturer->nidn = $lecturer->employee_card_serial_number;
                $clone_lecturer->password = $lecturer->password;
                $clone_lecturers->add($clone_lecturer);
            }
        }
        $clone_lecturers->unique('nidn');
        foreach ($clone_lecturers as $clone_lecturer)
        {
            $user = User::where('nidn', $clone_lecturer->nidn)->first();
            if ($user === null)
            {
                $user = new User();
                $user->nidn = $clone_lecturer->nidn;
                $user->password = $clone_lecturer->password;
                $user->save();
            }
        }
    }

    public function sendEmail()
    {
        $email = [];
        $email['recipient_name'] = 'Bapak/Ibu';
        $email['body_content'] = 'Kami informasikan bahwa anda telah didaftarkan pada usulan penelitian Universitas Sumatera Utara. Untuk itu, kami meminta Bapak/Ibu untuk melakukan verifikasi atas usulan tersebut. Untuk melakukan verifikasi, Bapak/Ibu diminta untuk login pada link ini: <a href="https://simpel.usu.ac.id">Sistem Penelitian USU</a>';
        $email['body_detail_content'] = 'Demikian informasi ini kami sampaikan.<br/>Dikirim otomatis oleh Sistem Penlitian USU';
        View::share('email', $email);
        Mail::to('kokorochi.zhou@gmail.com')->send(new TestMail($email));

        return redirect()->intended('/');
    }

    public function convertExcel()
    {
        $data = [
            ["firstname" => "Mary", "lastname" => "Johnson", "age" => 25],
            ["firstname" => "Amanda", "lastname" => "Miller", "age" => 18],
            ["firstname" => "James", "lastname" => "Brown", "age" => 31],
            ["firstname" => "Patricia", "lastname" => "Williams", "age" => 7],
            ["firstname" => "Michael", "lastname" => "Davis", "age" => 43],
            ["firstname" => "Sarah", "lastname" => "Miller", "age" => 24],
            ["firstname" => "Patrick", "lastname" => "Miller", "age" => 27]
        ];

        Excel::create('testexcel', function($excel) use($data){
            // Set the title
            $excel->setTitle('Our new awesome title');

            // Chain the setters
            $excel->setCreator('PSI')
                ->setCompany('PSI');

            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');

            $excel->sheet('sheet1', function($sheet) use($data){
                $sheet->fromArray($data, null, 'A1', true);
            });
        })->export('xlsx');
    }

    public function sso()
    {
        $login = JWTAuth::communicate('https://akun.usu.ac.id/auth/listen', @$_COOKIE['ssotok'], function ($credential)
        {
            $loggedIn = $credential->logged_in;
            if ($loggedIn)
            {
                //kalau udah login
            } else
            {
                setcookie('ssotok', null, -1, '/');

                return false;
            }
        }
        );
        dd($login);
    }

    public function callback()
    {
        return JWTAuth::recv([
            'ssotok'  => @$_GET['ssotok'],
            'secured' => true
        ]);
    }
}
