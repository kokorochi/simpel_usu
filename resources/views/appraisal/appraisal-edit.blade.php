@extends('layouts.lay_admin')

@section('content')
<section id="page-content">

    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-star"></i> {{ $pageTitle }} </h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">Direktori anda:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{url('dashboard/index')}}">Home</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    {{ $pageTitle }}
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Edit</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <div class="body-content animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">Edit {{$pageTitle}}</h3>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->

                    <div class="panel-body no-padding">
                        <form class="form-horizontal form-bordered" action="{{url($deleteUrl . '/' . $appraisal->id . '/edit')}}" method="POST">
                            <div class="form-body">
                                {{--<div class="form-footer">--}}
                                    {{--<div class="col-sm-offset-1">--}}
                                        {{--<a href="#" class="add_field_button btn btn-success btn-stroke"><i class="fa fa-plus"></i></a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="input_fields_wrap">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Nama Aspek Penilaian</label>
                                        <div class="col-sm-6">
                                            <input name="name" class="form-control input-sm" type="text" value="{{ empty(old('name')) ? $appraisal->name : old('name') }}">
                                            @if($errors->has('name'))
                                                <label class="error" for="name" style="display: inline-block;">
                                                    {{ $errors->first('name') }}
                                                </label>
                                            @endif
                                        </div>
                                    </div><!-- /.form-group -->

                                @if(count(old('aspect')) > 0)
                                    @foreach(old('aspect') as $b => $c)
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Deskripsi Aspek</label>
                                            <div class="col-sm-6">
                                                <input name="aspect[]" class="form-control input-sm" type="text" value="{{ old('aspect.' . $b) }}">
                                                @if($errors->has('aspect.' . $b))
                                                    <label class="error" for="aspect[]" style="display: inline-block;">
                                                        {{ $errors->first('aspect.' . $b) }}
                                                    </label>
                                                @endif
                                            </div>
                                            <label class="col-sm-1 control-label">Bobot</label>
                                            <div class="col-sm-2">
                                                <input name="quality[]" class="form-control input-sm" type="text" value="{{ old('quality.' . $b) }}">
                                                @if($errors->has('quality.' . $b))
                                                    <label class="error" for="quality[]" style="display: inline-block;">
                                                        {{ $errors->first('quality.' . $b) }}
                                                    </label>
                                                @endif
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="#" class="remove_field btn btn-sm btn-danger btn-stroke">
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div><!-- /.form-group -->
                                    @endforeach
                                @else
                                    @foreach($appraisals_is as $appraisals_i)
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Deskripsi Aspek</label>
                                            <div class="col-sm-6">
                                                <input name="aspect[]" class="form-control input-sm" type="text" value="{{ $appraisals_i->aspect }}">
                                            </div>
                                            <label class="col-sm-1 control-label">Bobot</label>
                                            <div class="col-sm-2">
                                                <input name="quality[]" class="form-control input-sm" type="text" value="{{ $appraisals_i->quality }}">
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="#" class="remove_field btn btn-sm btn-danger btn-stroke">
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div><!-- /.form-group -->
                                    @endforeach
                                @endif
                                </div><!-- /.input_fields_wrap -->

                                @if($errors->has('countQuality'))
                                    <div class="form-group">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-6">
                                            <label class="error control-label" for="countQuality" style="display: inline-block;">{{ $errors->first('countQuality') }}</label>
                                        </div>
                                    </div><!-- /.form-group -->
                                @endif

                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-footer">
                                    <div class="col-sm-offset-2">
                                        <a href="#" class="add_field_button btn btn-success btn-stroke"><i class="fa fa-plus"></i></a>
                                        <a href="{{url($deleteUrl)}}" class="btn btn-danger btn-slideright">Cancel</a>
                                        <button type="submit" class="btn btn-success btn-slideright">Submit</button>
                                    </div>
                                </div>
                            </div><!-- /.form-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
     </div><!-- /.body-content -->

    <!-- Start footer content -->
    @include('layouts._footer-admin')
    <!--/ End footer content -->
</section><!-- /#page-content -->
@endsection