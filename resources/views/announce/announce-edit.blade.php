@extends('layouts.lay_admin')

@section('content')
<section id="page-content">
    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-bullhorn"></i> Pengumuman </h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">Direktori anda:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{url('/')}}">Home</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    Pengumuman
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
                            <h3 class="panel-title">Edit Pengumuman</h3>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->

                    <div class="panel-body no-padding">
                        <form class="form-horizontal form-bordered" action="{{url('announces/' . $announce->id . '/edit')}}" method="POST">
                            <div class="form-body">
                                <div class="form-group">

                                    {{--<label class="col-sm-7 control-label">{{ $announce->created_by }}</label>--}}
                                    {{--<label class="col-sm-2 control-label">{{ $announce->created_at }}</label>--}}
                                    {{--<label class="col-sm-2 control-label">{{ $announce->updated_at }}</label>--}}
                                    <label class="col-sm-3 control-label">Dibuat oleh</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <input value="{{$announce->created_by}}" class="form-control input-sm mb-5" type="text" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <label class="col-sm-3 control-label">Dibuat Tanggal</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <input value="{{$announce->created_at}}" class="form-control input-sm mb-5" type="text" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <label class="col-sm-3 control-label">Dimodifikasi Tanggal</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <input value="{{$announce->updated_at}}" class="form-control input-sm mb-5" type="text" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.form-group -->

                                <div class="form-group">
                                    <label for="title" class="col-sm-3 control-label">Judul Pengumuman</label>
                                    <div class="col-sm-7">
                                        <input name="title" class="form-control" type="text" value="{{ empty(old('title')) ? $announce->title : old('title') }}">
                                        @if($errors->has('title'))
                                            <label class="error" for="title" style="display: inline-block;">
                                                {{ $errors->first('title') }}
                                            </label>
                                        @endif
                                    </div>
                                </div><!-- /.form-group -->

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="description" class="col-sm-3 control-label">Konten Pengumuman</label>
                                        <div class="col-sm-7">
                                            <textarea name="description" class="form-control input-sm" rows="12" placeholder="Enter text ...">{{ empty(old('description')) ? $announce->content : old('description') }}</textarea>
                                            @if($errors->has('description'))
                                                <label class="error" for="description" style="display: inline-block;">
                                                    {{ $errors->first('description') }}
                                                </label>
                                            @endif
                                        </div>
                                        {{--<textarea name="content" id="wysihtml5-textarea" class="form-control" rows="12" placeholder="Enter text ...">{{ $announce->content }}</textarea>--}}
                                    </div>
                                </div><!-- /.form-group -->

                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-footer">
                                    <div class="col-sm-offset-3">
                                        <a href="{{url('announces')}}" class="btn btn-danger btn-slideright">Cancel</a>
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