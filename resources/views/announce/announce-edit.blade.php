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
                                <button class="btn btn-sm" data-action="collapse" data-container="body"
                                        data-toggle="tooltip" data-placement="top" data-title="Collapse"><i
                                            class="fa fa-angle-up"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body no-padding">
                            <form class="form-horizontal form-bordered"
                                  enctype="multipart/form-data"
                                  action="{{url('announces/' . $announce->id . '/edit')}}" method="POST">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-sm-4 col-md-3 control-label">Dibuat oleh</label>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <input value="{{$announce->created_by}}"
                                                           class="form-control input-sm mb-5" type="text"
                                                           readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-4 col-md-3 control-label">Dibuat Tanggal</label>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <input value="{{$announce->created_at}}"
                                                           class="form-control input-sm mb-5" type="text"
                                                           readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-4 col-md-3 control-label">Dimodifikasi Tanggal</label>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <input value="{{$announce->updated_at}}"
                                                           class="form-control input-sm mb-5" type="text"
                                                           readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.form-group -->

                                    @if($announce->image_name !== null)
                                        <div class="form-group">
                                            <label class="control-label col-sm-4 col-md-3">Gambar Pengumuman</label>
                                            <div class="col-sm-7">
                                                <img src="{{url('images/upload/announces', $announce->image_name)}}"
                                                     class="announce-image mb-10" alt="">
                                            </div>
                                            <div class="clearfix"></div>
                                            <label class="control-label col-sm-4 col-md-3">Hapus Gambar</label>
                                            <div class="col-sm-7">
                                                <div class="ckbox ckbox-danger">
                                                    <input name="delete_image" id="checkbox-danger2" type="checkbox" value="x">
                                                    <label for="checkbox-danger2"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <div class="clearfix"></div>
                                        <label class="control-label col-sm-4 col-md-3">Unggah Gambar Pengumuman</label>
                                        <div class="col-sm-7">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control input-sm" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                            <span class="input-group-addon btn btn-success btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="image_name"
                                                       value="">
                                            </span>
                                                <a href="#" class="input-group-addon btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput">Remove</a>
                                            </div>
                                            @if($errors->has('image_name'))
                                                <label class="error" for="image_name"
                                                       style="display: inline-block;">
                                                    {{ $errors->first('image_name') }}
                                                </label>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="title" class="col-sm-4 col-md-3 control-label">Judul Pengumuman</label>
                                        <div class="col-sm-7">
                                            <input name="title" class="form-control" type="text"
                                                   value="{{ empty(old('title')) ? $announce->title : old('title') }}">
                                            @if($errors->has('title'))
                                                <label class="error" for="title" style="display: inline-block;">
                                                    {{ $errors->first('title') }}
                                                </label>
                                            @endif
                                        </div>
                                    </div><!-- /.form-group -->

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="description" class="col-sm-4 col-md-3 control-label">Konten
                                                Pengumuman</label>
                                            <div class="col-sm-7">
                                                <textarea name="description" class="form-control input-sm" rows="12"
                                                          placeholder="Enter text ...">{{ empty(old('description')) ? $announce->content : old('description') }}</textarea>
                                                @if($errors->has('description'))
                                                    <label class="error" for="description"
                                                           style="display: inline-block;">
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
                                            <a href="{{url('announces')}}"
                                               class="btn btn-danger btn-slideright">Cancel</a>
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