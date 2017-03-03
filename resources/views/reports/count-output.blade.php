@extends('layouts.lay_admin')

<!-- START @PAGE CONTENT -->
@section('content')
<section id="page-content">

    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-bar-chart"></i>Laporan</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">Direktori anda:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ url('/')}}">Beranda</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    Laporan
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">Luaran</li>
            </ol>
        </div>
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <!-- Start body content -->
    <div class="body-content animated fadeIn">

        <div class="row">
            <div class="col-md-12">

                <!-- Filter -->
                <div class="panel rounded shadow">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">Filter</h3>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                               data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.panel-heading -->
                    <div class="panel-body no-padding">
                        <form class="form-body form-horizontal output-filter" action="#">
                            <div class="form-group">
                                {{--<label for="input[level]" class="col-sm-4 col-md-3 control-label">Level</label>--}}
                                <div class="col-sm-2">
                                    <select name="input[level]" data-placeholder="Pilih Level" class="chosen-select-level mb-15" tabindex="2">
                                        <option value="1">Universitas Sumatera Utara</option>
                                        <option value="2">Fakultas</option>
                                        <option value="3">Program Studi</option>
                                        <option value="4">Dosen</option>
                                    </select>
                                </div> <!-- /.col-sm-7 -->
                            {{--</div> <!-- /.form-group -->--}}

                            <div class="faculty-wrapper">
                                {{--<div class="form-group">--}}
                                    {{--<label for="input[faculty]" class="col-sm-4 col-md-3 control-label">Fakultas</label>--}}
                                    <div class="col-sm-2">
                                        <select name="input[faculty]" data-placeholder="Pilih Fakultas" class="chosen-select-faculty mb-15" tabindex="2">
                                        </select>
                                    </div> <!-- /.col-sm-7 -->
                                {{--</div> <!-- /.form-group -->--}}
                            </div><!-- /.faculty-wrapper -->

                            <div class="study-program-wrapper">
                                {{--<div class="form-group">--}}
                                    {{--<label for="input[study_program]" class="col-sm-4 col-md-3 control-label">Program Studi</label>--}}
                                    <div class="col-sm-2">
                                        <select name="input[study_program]" data-placeholder="Pilih Program Studi" class="chosen-select-study-program mb-15" tabindex="2">
                                        </select>
                                    </div> <!-- /.col-sm-7 -->
                                {{--</div> <!-- /.form-group -->--}}
                            </div> <!-- /.study-program-wrapper -->

                            <div class="lecturer-wrapper">
                                {{--<div class="form-group">--}}
                                    {{--<label for="input[lecturer]" class="col-sm-4 col-md-3 control-label">Dosen</label>--}}
                                    <div class="col-sm-2">
                                        <select name="input[lecturer]" data-placeholder="Pilih Dosen" class="chosen-select-lecturer mb-15" tabindex="2">
                                        </select>
                                    </div> <!-- /.col-sm-7 -->
                                {{--</div> <!-- /.form-group -->--}}
                            </div> <!-- /.lecturer-wrapper -->

                            {{--<div class="form-group">--}}
                                {{--<label for="input[output]" class="col-sm-4 col-md-3 control-label">Luaran</label>--}}
                                <div class="col-sm-2">
                                    <select name="input[output]" data-placeholder="Pilih Luaran" class="chosen-select-output mb-15" tabindex="2">
                                    </select>
                                </div> <!-- /.col-sm-7 -->
                            {{--</div> <!-- /.form-group -->--}}

                            {{--<div class="form-group">--}}
                                {{--<label for="input[year]" class="col-sm-4 col-md-3 control-label">Tahun Akhir</label>--}}
                                <div class="col-sm-2">
                                    <input name="input[year]" class="form-control input-sm" type="text"
                                           value="" required>
                                </div> <!-- /.col-sm-7 -->
                            </div>
                            {{--</div> <!-- /.form-group -->--}}
                            <div class="form-footer">
                                <button type="button" class="btn btn-success btn-slideright submit">Filter</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->
                <!-- End Filter -->

            </div>
        </div><!-- /.row -->

    </div><!-- /.body-content -->
    <!--/ End body content -->

    <!-- Start footer content -->
    @include('layouts._footer-admin')
    <!--/ End footer content -->

</section><!-- /#page-content -->
@stop
<!--/ END PAGE CONTENT -->
