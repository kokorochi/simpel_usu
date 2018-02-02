<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">Informasi Print Lembar Pengesahan</h3>
                </div>
                <div class="pull-right">
                    <a class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                       data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body no-padding">
                <div class="form-body form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 control-label">Mengetahui</label>
                        <div class="col-md-7">
                            <div class="rdio rdio-theme circle">
                                @if($propose_relation->propose->faculty_code=="RS")
                                    <div class="radio-inline">
                                        <input id="dean" name="sign_1" type="radio" checked="checked" value="dean">
                                        <label for="dean">Direktur Utama</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_1" name="sign_1" type="radio" value="vice_dean_1">
                                        <label for="vice_dean_1">Direktur Pendidikan, Pelatihan, Penelitian dan Kerjasama</label>
                                    </div>
                                @elseif($propose_relation->propose->faculty_code=="SPS")
                                    <div class="radio-inline">
                                        <input id="dean" name="sign_1" type="radio" checked="checked" value="dean">
                                        <label for="dean">Direktur</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_1" name="sign_1" type="radio" value="vice_dean_1">
                                        <label for="vice_dean_1">Wakil Direktur I</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_2" name="sign_1" type="radio" value="vice_dean_2">
                                        <label for="vice_dean_2">Wakil Direktur II</label>
                                    </div>
                                @else
                                    <div class="radio-inline">
                                        <input id="dean" name="sign_1" type="radio" checked="checked" value="dean">
                                        <label for="dean">Dekan</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_1" name="sign_1" type="radio" value="vice_dean_1">
                                        <label for="vice_dean_1">Wakil Dekan 1</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_2" name="sign_1" type="radio" value="vice_dean_2">
                                        <label for="vice_dean_2">Wakil Dekan 2</label>
                                    </div>
                                    <div class="radio-inline">
                                        <input id="vice_dean_3" name="sign_1" type="radio" value="vice_dean_3">
                                        <label for="vice_dean_3">Wakil Dekan 3</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-md-3 control-label">Menyetujui</label>
                        <div class="col-md-7">
                            <div class="rdio rdio-theme circle">
                                <div class="radio-inline">
                                    <input id="head" name="sign_2" type="radio" checked="checked" value="head">
                                    <label for="head">Ketua LP</label>
                                </div>
                                <div class="radio-inline">
                                    <input id="secretary" name="sign_2" type="radio" value="secretary">
                                    <label for="secretary">Sekretaris LP</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.form-body -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-md-12 -->
</div><!-- /.row -->