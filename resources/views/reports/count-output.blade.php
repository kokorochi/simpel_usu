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
                                        <select name="input[level]" data-placeholder="Pilih Level"
                                                class="chosen-select-level mb-15" tabindex="2">
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
                                            <select name="input[faculty]" data-placeholder="Pilih Fakultas"
                                                    class="chosen-select-faculty mb-15" tabindex="2">
                                            </select>
                                        </div> <!-- /.col-sm-7 -->
                                        {{--</div> <!-- /.form-group -->--}}
                                    </div><!-- /.faculty-wrapper -->

                                    <div class="study-program-wrapper">
                                        {{--<div class="form-group">--}}
                                        {{--<label for="input[study_program]" class="col-sm-4 col-md-3 control-label">Program Studi</label>--}}
                                        <div class="col-sm-2">
                                            <select name="input[study_program]" data-placeholder="Pilih Program Studi"
                                                    class="chosen-select-study-program mb-15" tabindex="2">
                                            </select>
                                        </div> <!-- /.col-sm-7 -->
                                        {{--</div> <!-- /.form-group -->--}}
                                    </div> <!-- /.study-program-wrapper -->

                                    <div class="lecturer-wrapper">
                                        {{--<div class="form-group">--}}
                                        {{--<label for="input[lecturer]" class="col-sm-4 col-md-3 control-label">Dosen</label>--}}
                                        <div class="col-sm-2">
                                            <select name="input[lecturer]" data-placeholder="Pilih Dosen"
                                                    class="chosen-select-lecturer mb-15" tabindex="2">
                                            </select>
                                        </div> <!-- /.col-sm-7 -->
                                        {{--</div> <!-- /.form-group -->--}}
                                    </div> <!-- /.lecturer-wrapper -->

                                    {{--<div class="form-group">--}}
                                    {{--<label for="input[output]" class="col-sm-4 col-md-3 control-label">Luaran</label>--}}
                                    <div class="col-sm-2">
                                        <select name="input[output]" data-placeholder="Pilih Luaran"
                                                class="chosen-select-output mb-15" tabindex="2">
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

                    <!-- Reporting -->
                    <div class="panel rounded shadow">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h3 class="panel-title">Laporan</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                                   data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body no-padding">
                            <!-- Start repeater -->
                            <div class="fuelux count-output-fuelux">
                                <div class="repeater" data-staticheight="400" id="count-output-repeater">
                                    <div class="repeater-header">
                                        <div class="repeater-header-left">
                                            <div class="repeater-search">
                                                <div class="search input-group">
                                                    <input type="search" class="form-control" placeholder="Search"/>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                            <span class="sr-only">Pencarians</span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="repeater-header-right">
                                            {{--<div class="btn-group selectlist repeater-filters" data-resize="auto">--}}
                                                {{--<button type="button" class="btn btn-success dropdown-toggle"--}}
                                                        {{--data-toggle="dropdown">--}}
                                                    {{--<span class="selected-label">&nbsp;</span>--}}
                                                    {{--<span class="caret"></span>--}}
                                                    {{--<span class="sr-only">Toggle Filters</span>--}}
                                                {{--</button>--}}
                                                {{--<ul id="test" class="dropdown-menu" role="menu">--}}
                                                    {{--<li data-value="all" data-selected="true" class="text-left"><a--}}
                                                                {{--href="#">All Filter</a></li>--}}
                                                    {{--<li data-value="music"><a href="#">Music</a></li>--}}
                                                    {{--<li data-value="electronics"><a href="#">Electronics</a></li>--}}
                                                    {{--<li data-value="fashion"><a href="#">Fashion</a></li>--}}
                                                    {{--<li data-value="home_garden"><a href="#">Home & garden</a></li>--}}
                                                    {{--<li data-value="sport"><a href="#">Sporting goods</a></li>--}}
                                                {{--</ul>--}}
                                                {{--<input class="hidden hidden-field" name="filterSelection"--}}
                                                       {{--readonly="readonly" aria-hidden="true" type="text"/>--}}
                                            {{--</div>--}}
                                            {{--<div class="btn-group repeater-views" data-toggle="buttons">--}}
                                                {{--<label class="btn btn-success active">--}}
                                                    {{--<input name="repeaterViews" type="radio" value="list"><span--}}
                                                            {{--class="glyphicon glyphicon-list"></span>--}}
                                                {{--</label>--}}
                                                {{--<label class="btn btn-success">--}}
                                                    {{--<input name="repeaterViews" type="radio" value="thumbnail"><span--}}
                                                            {{--class="glyphicon glyphicon-th"></span>--}}
                                                {{--</label>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <div class="repeater-viewport">
                                        <div class="repeater-canvas"></div>
                                        <div class="loader repeater-loader"></div>
                                    </div>
                                    <div class="repeater-footer">
                                        <div class="repeater-footer-left">
                                            <div class="repeater-itemization">
                                                <span><span class="repeater-start"></span> - <span
                                                            class="repeater-end"></span> of <span
                                                            class="repeater-count"></span> items</span>
                                                <div class="btn-group selectlist" data-resize="auto">
                                                    <button type="button" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <span class="selected-label">&nbsp;</span>
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li data-value="5"><a href="#">5</a></li>
                                                        <li data-value="10" data-selected="true"><a href="#">10</a></li>
                                                        <li data-value="20"><a href="#">20</a></li>
                                                        <li data-value="50" data-foo="bar" data-fizz="buzz"><a href="#">50</a>
                                                        </li>
                                                        <li data-value="100"><a href="#">100</a></li>
                                                    </ul>
                                                    <input class="hidden hidden-field" name="itemsPerPage"
                                                           readonly="readonly" aria-hidden="true" type="text"/>
                                                </div>
                                                <span>Per Page</span>
                                            </div>
                                        </div>
                                        <div class="repeater-footer-right">
                                            <div class="repeater-pagination">
                                                <button type="button" class="btn btn-default btn-sm repeater-prev">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                    <span class="sr-only">Previous Page</span>
                                                </button>
                                                <label class="page-label" id="myPageLabel">Page</label>
                                                <div class="repeater-primaryPaging active">
                                                    <div class="input-group input-append dropdown combobox">
                                                        <input type="text" class="form-control"
                                                               aria-labelledby="myPageLabel">
                                                        <div class="input-group-btn">
                                                            <button type="button"
                                                                    class="btn btn-default dropdown-toggle"
                                                                    data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control repeater-secondaryPaging"
                                                       aria-labelledby="myPageLabel">
                                                <span>of <span class="repeater-pages"></span></span>
                                                <button type="button" class="btn btn-default btn-sm repeater-next">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                    <span class="sr-only">Next Page</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ End repeater -->
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                    <!-- End Reporting -->

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
