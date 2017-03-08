@extends('layouts.lay_account')

<!-- START @SIGN WRAPPER -->
@section('content')
    <div id="sign-wrapper">

        <!-- Brand -->
        <div class="brand">
            <a href="{{url('/')}}"><img src="{{$assetUrl}}images/Logo LP USU 2.png" alt="brand logo"/></a>
        </div>
        <!--/ Brand -->

        <!-- Lost password form -->
        <form class="form-horizontal rounded shadow" action="{{url('user/lost')}}" method="post">
            <div class="sign-header">
                <div class="form-group">
                    <div class="sign-text">
                        <span>Reset password</span>
                    </div>
                </div>
            </div>
            <div class="sign-body">
                <div class="form-group">
                    <div class="input-group input-group-lg rounded">
                        <input name="input[nidn]" type="text" class="form-control" placeholder="NIDN">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    </div>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">

            <div class="sign-footer">
                @if($errors->has('input.nidn'))
                    <div class="form-group">
                        <label id="login-error" class="error" for="input[nidn]">
                            {{ $errors->first('input.nidn') }}
                        </label>
                    </div>
                @elseif($errors->has('sumErrors'))
                    <div class="form-group">
                        <label id="login-error" class="error" for="password">
                            {{$errors->first('sumErrors')}}
                        </label>
                    </div>
                @endif
                @if(Session::has('alert-success'))
                    <div class="form-group">
                        <label class="alert alert-success" for="password" style="display: block; text-align: center;">
                            {{Session::get('alert-success')}}
                        </label>
                    </div>
                @endif
                &nbsp
                <div class="form-group">
                    <button type="submit" class="btn btn-theme btn-lg btn-block no-margin rounded">Kirim email</button>
                </div>
            </div>
        </form>
        <!--/ Lost password form -->

        <!-- Content text -->
        <p class="text-muted text-center sign-link">Kembali ke <a href="{{url('user/login')}}"> halaman login</a></p>
        <!--/ Content text -->

    </div><!-- /#sign-wrapper -->
@stop
<!--/ END SIGN WRAPPER -->