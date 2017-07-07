@extends('layouts.lay_admin')

@section('content')
    <section id="page-content">

        <!-- Start page header -->
        <div class="header-content">
            <h2><i class="fa fa-upload"></i> Batch Input </h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori anda:</span>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{url('/')}}">Beranda</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        Batch Input
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li class="active">Select File</li>
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
                                <h3 class="panel-title">{{$pageTitle}}</h3>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-sm" data-action="collapse" data-container="body"
                                        data-toggle="tooltip" data-placement="top" data-title="Collapse"><i
                                            class="fa fa-angle-up"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body no-padding">
                            <form action="{{url('batch-input')}}" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-4 col-md-3">File</label>
                                    <div class="col-sm-7">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control input-sm" data-trigger="fileinput"><i
                                                        class="glyphicon glyphicon-file fileinput-exists"></i> <span
                                                        class="fileinput-filename"></span></div>
                                                            <span class="input-group-addon btn btn-success btn-file">
                                                                <span class="fileinput-new">Pilih file</span>
                                                                <span class="fileinput-exists">Ubah</span>
                                                                <input type="file" name="excel_file">
                                                            </span>
                                            <a href="#" class="input-group-addon btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput">Hapus</a>
                                        </div>
                                    </div>
                                </div><!-- /.form-group -->
                                <div class="clearfix"></div>

                                {{ csrf_field() }}

                                <div class="form-footer">
                                    <div class="col-sm-offset-3">
                                        <button type="submit" class="btn btn-success btn-slideright submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.body-content -->
    </section>
@endsection