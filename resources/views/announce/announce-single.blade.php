@extends('layouts.lay_admin')

<!-- START @PAGE CONTENT -->
@section('content')
<section id="page-content">

    <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-file-text"></i> Detail Pengumuman </h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">Direktori anda:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{url('dashboard/index')}}">Dashboard</a>
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
                            <li>Oleh: {{ $announce->created_by }}</li>
                            <li><?php echo date("d M Y", strtotime($announce->created_at)) ?></li>
                            {{--<li><a href="">7 Comments</a></li>--}}
                        </ul>
                        {{--<div class="blog-img">--}}
                            {{--<img src="http://img.djavaui.com/?create=750x500,4888E1?f=ffffff" class="img-responsive" alt="...">--}}
                        {{--</div>--}}
                        {!! $announce->content !!}
                        {{--{{ $announce->content }}--}}

                    </div><!-- panel-body -->
                </div><!-- panel-blog -->

                {{--<div class="blog-author">--}}
                    {{--<div class="media">--}}
                        {{--<a class="pull-left" href="#">--}}
                            {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=100x100,6880B0?f=ffffff" alt="...">--}}
                        {{--</a>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">About The Author</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                        {{--</div>--}}
                    {{--</div><!-- media -->--}}
                {{--</div><!-- blog-author -->--}}

                {{--<h5 class="comment-count">7 Comments</h5>--}}

                {{--<ul class="media-list comment-list">--}}

                    {{--<li class="media">--}}
                        {{--<div class="media-left">--}}
                            {{--<a href="#">--}}
                                {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                            {{--<h4>Daddy Botak</h4>--}}
                            {{--<small class="text-muted">Juni 16, 2014 at 7:30am</small>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                        {{--</div>--}}
                    {{--</li><!-- media -->--}}

                    {{--<li class="media">--}}
                        {{--<div class="media-left">--}}
                            {{--<a href="#">--}}
                                {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                            {{--<h4>Sarah Tingting</h4>--}}
                            {{--<small class="text-muted">Juni 16, 2014 at 7:30am</small>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}

                            {{--<div class="media">--}}
                                {{--<div class="media-left">--}}
                                    {{--<a href="#">--}}
                                        {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                                {{--<div class="media-body">--}}
                                    {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                                    {{--<h4>Harry Potret</h4>--}}
                                    {{--<small class="text-muted">Juni 16, 2014 at 7:30am</small>--}}
                                    {{--<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                                {{--</div>--}}
                            {{--</div><!-- media -->--}}

                        {{--</div>--}}
                    {{--</li><!-- media -->--}}

                    {{--<li class="media">--}}
                        {{--<div class="media-left">--}}
                            {{--<a href="#">--}}
                                {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                            {{--<h4>Claudia Cinta</h4>--}}
                            {{--<small class="text-muted">Juni 17, 2014 at 6:30am</small>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>--}}

                            {{--<div class="media">--}}
                                {{--<div class="media-left">--}}
                                    {{--<a href="#">--}}
                                        {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                                {{--<div class="media-body">--}}
                                    {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                                    {{--<h4>Daniel Miring</h4>--}}
                                    {{--<small class="text-muted">Juni 17, 2014 at 6:30am</small>--}}
                                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>--}}

                                    {{--<div class="media">--}}
                                        {{--<div class="media-left">--}}
                                            {{--<a href="#">--}}
                                                {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                                            {{--</a>--}}
                                        {{--</div>--}}
                                        {{--<div class="media-body">--}}
                                            {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                                            {{--<h4>Jeck Joko</h4>--}}
                                            {{--<small class="text-muted">Juni 17, 2014 at 6:30am</small>--}}
                                            {{--<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                                        {{--</div>--}}
                                    {{--</div><!-- media -->--}}

                                {{--</div><!-- media-body -->--}}
                            {{--</div><!-- media -->--}}

                            {{--<div class="media">--}}
                                {{--<div class="media-left">--}}
                                    {{--<a href="#">--}}
                                        {{--<img class="media-object thumbnail" src="http://img.djavaui.com/?create=50x50,6880B0?f=ffffff" alt="...">--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                                {{--<div class="media-body">--}}
                                    {{--<a href="#" class="btn btn-success btn-xs pull-right rounded">Reply</a>--}}
                                    {{--<h4>Tenny Imoet</h4>--}}
                                    {{--<small class="text-muted">Juni 17, 2014 at 6:30am</small>--}}
                                    {{--<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
                                {{--</div>--}}
                            {{--</div><!-- media -->--}}

                        {{--</div><!-- media-body -->--}}

                    {{--</li><!-- media -->--}}

                {{--</ul><!-- comment-list -->--}}
                {{--<br/>--}}
                {{--<h5 class="comment-title mb-5">Leave A Comment</h5>--}}
                {{--<p class="text-muted">Your email address will not be published.</p>--}}
                {{--<div class="mb-20"></div>--}}

                {{--<form class="form-horizontal mb-20" role="form">--}}
                    {{--<div class="form-group no-margin">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-4">--}}
                                {{--<input type="text" class="form-control mb-15" placeholder="Name"/>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4">--}}
                                {{--<input type="text" class="form-control mb-15" placeholder="Email"/>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4">--}}
                                {{--<input type="text" class="form-control mb-15" placeholder="Website"/>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control" placeholder="Title"/>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<textarea id="comment-form" class="form-control" rows="5" placeholder="Comment goes here ..."></textarea>--}}
                    {{--</div>--}}
                    {{--<div class="form-group no-margin no-padding">--}}
                        {{--<button type="submit" class="btn btn-success pull-right rounded">Add Comment</button>--}}
                    {{--</div>--}}
                {{--</form>--}}

            </div>

            {{--<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">--}}
                {{--<div class="blog-sidebar">--}}
                    {{--<h5 class="blog-subtitle">Title</h5>--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>--}}

                    {{--<div class="panel transparent">--}}
                        {{--<div class="panel-heading no-border">--}}
                            {{--<h4 class="no-margin">Flickr</h4>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body no-padding transparent">--}}
                            {{--<div class="blog-gallery">--}}
                                {{--<ul class="list-inline">--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,81B71A?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,A90329?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,F4645F?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,6880B0?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,5a67a5?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="#"><img src="http://img.djavaui.com/?create=64x40,DD4814?f=ffffff" alt="..."/></a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-theme shadow">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<h3 class="panel-title">Categories</h3>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body no-padding">--}}
                            {{--<div class="list-group no-margin">--}}
                                {{--<a href="#" class="list-group-item">--}}
                                    {{--Nature--}}
                                {{--</a>--}}
                                {{--<a href="#" class="list-group-item">Entertainment</a>--}}
                                {{--<a href="#" class="list-group-item">Technology</a>--}}
                                {{--<a href="#" class="list-group-item">Food &amp; Health</a>--}}
                                {{--<a href="#" class="list-group-item">Movies &amp; TV Shows</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-theme shadow">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<h3 class="panel-title">Tags</h3>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body">--}}
                            {{--<ul class="list-inline blog-tags">--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> People </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> Nature </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> Phone </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> Internet </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> Photos </a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#"><i class="fa fa-tags"></i> Business </a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-theme shadow">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<h3 class="panel-title">Archives</h3>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body no-padding">--}}
                            {{--<div class="list-group no-margin">--}}
                                {{--<a href="#" class="list-group-item">Juni 2014</a>--}}
                                {{--<a href="#" class="list-group-item">Mei 2014</a>--}}
                                {{--<a href="#" class="list-group-item">April 2014</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-theme shadow blog-list-slider">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<h3 class="panel-title">NEWS & UPDATES</h3>--}}
                        {{--</div>--}}
                        {{--<div id="carousel-blog-list" class="carousel slide" data-ride="carousel">--}}
                            {{--<ol class="carousel-indicators">--}}
                                {{--<li data-target="#carousel-blog-list" data-slide-to="0" class="active"></li>--}}
                                {{--<li data-target="#carousel-blog-list" data-slide-to="1"></li>--}}
                            {{--</ol>--}}
                            {{--<div class="carousel-inner">--}}
                                {{--<div class="item active">--}}
                                    {{--<div class="blog-list">--}}
                                        {{--<div class="media">--}}
                                            {{--<a class="pull-left" href="#">--}}
                                                {{--<img src="http://img.djavaui.com/?create=45x30,A90329?f=ffffff" alt="..." class="img-responsive img-thumbnail"/>--}}
                                            {{--</a>--}}
                                            {{--<div class="media-body">--}}
                                                {{--<h4 class="media-heading"><a href="blog-single.html" title="Lorem ipsum dolor sit">Lorem ipsum dolor sit</a></h4>--}}
                                                {{--<small class="media-desc">Amet, consectetur adipisicing elit, sed do ut labore et dolore magna aliqua...</small>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="media">--}}
                                            {{--<a class="pull-left" href="#">--}}
                                                {{--<img src="http://img.djavaui.com/?create=45x30,F4645F?f=ffffff" alt="..." class="img-responsive img-thumbnail"/>--}}
                                            {{--</a>--}}
                                            {{--<div class="media-body">--}}
                                                {{--<h4 class="media-heading"><a href="blog-single.html" title="Ut enim ad minim veniam">Ut enim ad minim veniam</a></h4>--}}
                                                {{--<small class="media-desc">Quis nostrud exercitation ullamco laboris nisi ut aliquip ex commodo...</small>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="item">--}}
                                    {{--<div class="blog-list">--}}
                                        {{--<div class="media">--}}
                                            {{--<a class="pull-left" href="#">--}}
                                                {{--<img src="http://img.djavaui.com/?create=45x30,6880B0?f=ffffff" alt="..." class="img-responsive img-thumbnail"/>--}}
                                            {{--</a>--}}
                                            {{--<div class="media-body">--}}
                                                {{--<h4 class="media-heading"><a href="blog-single.html" title="Excepteur sint occaecat cupidatat">Excepteur sint occaecat cupidatat</a></h4>--}}
                                                {{--<small class="media-desc">Sunt in culpa qui officia deserunt mollit anim id est laborum...</small>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="media">--}}
                                            {{--<a class="pull-left" href="#">--}}
                                                {{--<img src="http://img.djavaui.com/?create=45x30,5a67a5?f=ffffff" alt="..." class="img-responsive img-thumbnail"/>--}}
                                            {{--</a>--}}
                                            {{--<div class="media-body">--}}
                                                {{--<h4 class="media-heading"><a href="blog-single.html" title="Sed ut perspiciatis unde">Sed ut perspiciatis unde</a></h4>--}}
                                                {{--<small class="media-desc">Omnis iste natus error sit voluptatem accusantium...</small>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div><!-- /.blog-list-slider -->--}}

                    {{--<div class="panel transparent">--}}
                        {{--<div class="panel-heading no-border">--}}
                            {{--<h4 class="no-margin">Recent Twitts</h4>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body no-padding transparent">--}}
                            {{--<div class="blog-twitter">--}}
                                {{--<div class="blog-twitter-list">--}}
                                    {{--<a href="https://twitter.com/djavaui" target="_blank"> @djavaui </a>--}}
                                    {{--<p>--}}
                                        {{--RELEASED NEW VERSION Blankon Yii2 Framework version.--}}
                                    {{--</p>--}}
                                    {{--<a href="https://t.co/kvwQUHCg0w" target="_blank">--}}
                                        {{--<em>https://t.co/kvwQUHCg0w</em>--}}
                                    {{--</a>--}}
                                    {{--<span> 3 hours ago </span>--}}
                                    {{--<i class="fa fa-twitter blog-twitter-icon"></i>--}}
                                {{--</div>--}}
                                {{--<div class="blog-twitter-list">--}}
                                    {{--<a href="https://twitter.com/djavaui" target="_blank"> @djavaui </a>--}}
                                    {{--<p>--}}
                                        {{--Blankon AngularJS version 1.0.1, Many feature are present.--}}
                                    {{--</p>--}}
                                    {{--<a href="https://t.co/kvwQUHCg0w" target="_blank">--}}
                                        {{--<em>https://t.co/kvwQUHCg0w</em>--}}
                                    {{--</a>--}}
                                    {{--<span> 1 hours ago </span>--}}
                                    {{--<i class="fa fa-twitter blog-twitter-icon"></i>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</div><!-- blog-sidebar -->--}}
            {{--</div>--}}

        </div><!-- row -->

    </div><!-- /.body-content -->
    <!--/ End body content -->

    <!-- Start footer content -->
    @include('layouts._footer-admin')
    <!--/ End footer content -->

</section><!-- /#page-content -->
@stop
<!--/ END PAGE CONTENT -->
