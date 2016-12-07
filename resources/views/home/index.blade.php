@extends('layouts.lay_admin')

<!-- START @PAGE CONTENT -->
@section('content')
<div id="page-content">

    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-home"></i> Home </h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">Direktori anda: </span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{url('dashboard/index')}}">Home</a>
                </li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <!-- Start body content -->
    <div class="body-content animated fadeIn">

        <!--

        Start blog list
        |=========================================================================================================================|
        |  TABLE OF CONTENTS                                                                               |
        |=========================================================================================================================|
        |  01. blog-grid                |  Variant style blog post type grid                                                      |
        |  02. blog-list                |  Variant style blog post type list                                                      |
        |=========================================================================================================================|

        -->

        <div id="blog-list" class="announces">
            <div class="row">
                <div class="col-md-12">
                    @foreach($announces as $announce)
                    <div class="blog-item blog-quote rounded shadow">
                        {{--<div class="quote quote-primary">--}}
                            {{--<a href="blog-single.html">--}}
                                {{--{{ $announce->title }}--}}
                                {{--<small class="quote-author">{{ $announce->created_by }}</small>--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        <div class="blog-details">
                            <h4 class="blog-title"><a href="{{ url('announces/' . $announce->id) }}">{{ $announce->title }}</a></h4>
                            <ul class="blog-meta">
                                <li>By: {{ $announce->created_by }}</li>
                                <li>
                                    <?php echo date("d M Y", strtotime($announce->created_at)) ?>
                                </li>
                                {{--<li><a href="">3 Comments</a></li>--}}
                            </ul>
                            <div class="blog-summary">
                                <p>{{ $announce->content }}</p>
                                <a href="{{ url('announces/' . $announce->id) }}" class="btn btn-sm btn-success">Read More</a>
                            </div>
                        </div><!-- blog-details -->
                    </div><!-- /.blog-item -->
                    @endforeach
                </div>
            </div>
        </div><!-- /#blog-list -->

        <!--/ End blog-list -->
        {{ $announces->render() }}

{{--        <p class="text-center"><img src="{{$assetUrl}}global/img/loader/general/2.gif" alt="..."/> Load more post</p>--}}

    </div><!-- /.body-content -->
    <!--/ End body content -->

    <!-- Start footer content -->
    @include('layouts._footer-admin')
    <!--/ End footer content -->

</section><!-- /#page-content -->
@stop
<!--/ END PAGE CONTENT -->
