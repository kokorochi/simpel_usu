@extends('layouts.lay_admin')

@section('content')
    <section id="page-content">

        <!-- Start page header -->
        <div class="header-content">
            <h2><i class="fa fa-bullhorn"></i> Testing Revieswers</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori anda:</span>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{url('/')}}">Home</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        Testing add reviewers dynamically
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li class="active">dynamically</li>
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
                                <h3 class="panel-title">Dyanamically</h3>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->

                        <div class="panel-body no-padding">

                            <form class="form-horizontal form-bordered" action="{{url('files/upload')}}" method="POST" enctype="multipart/form-data">
                                <div class="form-body">
                                    <div class="form-group">
                                        <table id="table-approve-propose-test" class="table table-striped table-success">
                                            <thead>
                                            <tr>
                                                <th data-class="expand">No</th>
                                                <th data-class="phone">Judul</th>
                                                <th data-hide="phone">Ketua</th>
                                                <th data-hide="phone">Scheme</th>
                                                <th data-hide="phone">Status</th>
                                                <th data-hide="phone,tablet">Reviewer 1</th>
                                                <th data-hide="phone,tablet">Reviewer 2</th>
                                            </tr>
                                            </thead>
                                            <!--tbody section is required-->
                                            <tbody></tbody>
                                            <!--tfoot section is optional-->
                                            <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>Ketua</th>
                                                <th>Scheme</th>
                                                <th>Status</th>
                                                <th data-hide="phone,tablet">Reviewer 1</th>
                                                <th data-hide="phone,tablet">Reviewer 2</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div><!-- /.form-group -->

                                    {{ csrf_field() }}
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
