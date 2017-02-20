@extends('layouts.lay_admin')

<!-- START @PAGE CONTENT -->
@section('content')
    <section id="page-content">

        <!-- Start page header -->
        <div class="header-content">
            <h2><i class="fa fa-bullhorn"></i>Detail Pengumuman</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori anda:</span>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{url('dashboard/index')}}">Beranda</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li class="active">Detail Pengumuman</li>
                </ol>
            </div><!-- /.breadcrumb-wrapper -->
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">

            <div class="row" id="blog-single">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="panel panel-default panel-blog rounded shadow">

                        <div class="panel-body">
                            <h3 class="blog-title">{{ $announce->title }}</h3>
                            <ul class="blog-meta">
                                <li>Oleh: {{ $announce->created_by_name }}</li>
                                <li><?php echo date("d M Y", strtotime($announce->created_at)) ?></li>
                            </ul>
                            <hr>
                            @if($announce->image_name !== null)
                                <div>
                                    <img src="{{url('images/upload/announces', $announce->image_name)}}" alt=""
                                         class="img-responsive" id="announce-img">
                                </div>
                                <hr>
                            @endif
                            @if($announce->share_file !== null)
                                <div class="form-body form-horizontal form-bordered">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-3">Unduh File</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input name="file_progress_activity" class="form-control input-sm"
                                                       type="text" disabled
                                                       value="{{ $announce->share_file_ori }}">
                                                <span class="input-group-btn">
                                                            <a href="{{url('files/upload/announces', $announce->share_file)}}"
                                                               class="btn btn-primary btn-sm" target="_blank">Unduh</a>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                            @endif
                            {!! $announce->content !!}
                        </div><!-- panel-body -->
                    </div><!-- panel-blog -->
                </div>
            </div><!-- row -->

        </div><!-- /.body-content -->
        <!--/ End body content -->

        <!-- Start footer content -->
    @include('layouts._footer-admin')
    <!--/ End footer content -->

    </section><!-- /#page-content -->
@stop
<!--/ END PAGE CONTENT -->
