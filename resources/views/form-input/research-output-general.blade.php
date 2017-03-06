{{--Get Old Value And Place It To VARIABLE--}}
@php($ctr = 0)

@php
    $olds = session()->getOldInput();

    if(!empty($olds))
    {
        foreach ($olds as $key => $old)
        {
            if(is_array($old))
            {
                foreach ($old as $key_2 => $old_2)
                {
                    $research_output_general = $research_output_generals->get($key_2);
                    if($research_output_general === null)
                    {
                        $research_output_generals->add(new \App\ResearchOutputGeneral());
                    }

                    $research_output_generals[$key_2][$key] = $old_2;
                }
            }
        }
    }
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">Luaran</h3>
                </div>
                <div class="pull-right">
                    <a class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                       data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body no-padding">
                <form action="{{url($deleteUrl, $research->id) . '/output-general'}}" method="post"
                      enctype="multipart/form-data"
                      class="form-body form-horizontal form-bordered submit-form"
                      id="input-mask">
                    @if($output_code === 'RL')
                        <div class="form-group">
                            @include('form-input.research-approve-revisiontext')
                        </div>
                        <hr>
                    @endif
                    <div class="research-general-wrapper">
                        @foreach($research_output_generals as $key => $research_output_general)
                            <div class="form-group">
                                <label for="status[{{$key}}]" class="col-sm-4 col-md-3 control-label">Jenis</label>
                                <div class="col-sm-7">
                                    <div class="rdio rdio-theme circle pull-left mr-10">
                                        <input id="radio-draft[{{$key}}]" value="draft" type="radio"
                                               name="status[{{$key}}]" {{$research_output_general->status === 'draft' ? 'checked' : ''}} {{$disabled}}>
                                        <label for="radio-draft[{{$key}}]">Draft</label>
                                    </div>
                                    <div class="rdio rdio-theme circle pull-left mr-10">
                                        <input id="radio-submitted[{{$key}}]" value="submitted" type="radio"
                                               name="status[{{$key}}]"{{$research_output_general->status === 'submitted' ? 'checked' : ''}} {{$disabled}}>
                                        <label for="radio-submitted[{{$key}}]">Submitted</label>
                                    </div>
                                    <div class="rdio rdio-theme circle pull-left mr-10">
                                        <input id="radio-accepted[{{$key}}]" value="accepted" type="radio"
                                               name="status[{{$key}}]"{{$research_output_general->status === 'accepted' ? 'checked' : ''}} {{$disabled}}>
                                        <label for="radio-accepted[{{$key}}]">Accepted</label>
                                    </div>
                                    <div class="rdio rdio-theme circle pull-left mr-10">
                                        <input id="radio-publish[{{$key}}]" value="publish" type="radio"
                                               name="status[{{$key}}]"{{$research_output_general->status === 'publish' ? 'checked' : ''}} {{$disabled}}>
                                        <label for="radio-publish[{{$key}}]">Publish</label>
                                    </div>
                                </div>
                                @if($research_output_general->file_name_ori !== null)
                                    @if($upd_mode !== 'approve' && $status_code !== 'PS')
                                        <div class="remove-output-wrapper">
                                            <div class="clearfix"></div>
                                            <label class="control-label col-sm-4 col-md-3">Hapus Luaran</label>
                                            <div class="col-sm-6 mb-10">
                                                <div class="ckbox ckbox-danger">
                                                    <input name="delete_output[{{$key}}]" type="hidden" value="0">
                                                    <input name="delete_output[{{$key}}]" id="checkbox-danger{{$key}}"
                                                           type="checkbox" value="1">
                                                    <label for="checkbox-danger{{$key}}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="download-output-wrapper">
                                        <div class="clearfix"></div>
                                        <label class="control-label col-sm-4 col-md-3">Unduh Luaran</label>
                                        <div class="col-sm-6 mb-10">
                                            <div class="input-group">
                                                <input name="file_name_ori[]" class="form-control input-sm"
                                                       type="text" disabled
                                                       value="{{ $research_output_general->file_name_ori }}">
                                                <span class="input-group-btn">
                                                    <a href="{{url('researches', $research_output_general->id) . '/output-download' }}"
                                                       class="btn btn-primary btn-sm" target="_blank">Unduh</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="output-year-wrapper">
                                    <div class="clearfix"></div>
                                    <label for="year[]" class="control-label col-sm-4 col-md-3">Tahun
                                        Luaran</label>
                                    <div class="col-sm-6 mb-10">
                                        <input name="year[]" class="form-control input-sm" type="text"
                                               maxlength="4"
                                               data-inputmask="'alias': 'decimal', 'rightAlign': false"
                                               value="{{ $research_output_general->year }}" {{$disabled}}>
                                        @if($errors->has('year.' . $key))
                                            <label class="error" for="year[]"
                                                   style="display: inline-block;">
                                                {{ $errors->first('year.' . $key) }}
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <label for="output_description[]" class="control-label col-sm-4 col-md-3">Detail
                                    Publikasi</label>
                                <div class="col-sm-6 mb-10">
                                    <input name="output_description[]" class="form-control input-sm" type="text"
                                           value="{{ $research_output_general->output_description }}" {{$disabled}}>
                                    @if($errors->has('output_description.' . $key))
                                        <label class="error" for="output_description[]"
                                               style="display: inline-block;">
                                            {{ $errors->first('output_description.' . $key) }}
                                        </label>
                                    @endif
                                </div>

                                <div class="clearfix"></div>
                                <label for="url_address[]" class="control-label col-sm-4 col-md-3">Url Address</label>
                                <div class="col-sm-6 mb-10">
                                    <input name="url_address[]" class="form-control input-sm" type="text"
                                           value="{{ $research_output_general->url_address }}" {{$disabled}}>
                                    @if($errors->has('url_address.' . $key))
                                        <label class="error" for="url_address[]"
                                               style="display: inline-block;">
                                            {{ $errors->first('url_address.' . $key) }}
                                        </label>
                                    @endif
                                </div>

                                <div class="clearfix"></div>
                                <div class="member-wrapper">
                                    @php($lv_output_members = $output_members->get($key))
                                    @if(! $lv_output_members->isEmpty())
                                        <hr/>
                                    @endif
                                    @foreach($lv_output_members as $member_key => $output_member)
                                        <div class="clone-member-wrapper">
                                            <div class="clearfix"></div>
                                            <label class="control-label col-sm-4 col-md-3">Dosen Luar</label>
                                            <div class="col-sm-7 mb-10">
                                                <div class="ckbox ckbox-default">
                                                    <input name="is_external[{{$key}}][{{$member_key}}]"
                                                           id="is_external[{{$key}}][{{$member_key}}]"
                                                           type="checkbox" value="1"
                                                           class="external-output-checkbox" {{$output_member->external === null ? "" : "checked"}} {{$disabled}}>
                                                    <label for="is_external[{{$key}}][{{$member_key}}]">*Tick ini jika
                                                        penulis bukan
                                                        dosen USU</label>
                                                </div>
                                            </div>
                                            <div class="external-member-wrapper">
                                                <label for="external[{{$key}}][]"
                                                       class="col-sm-4 col-md-3 control-label">Nama Penulis</label>
                                                <div class="col-sm-6 input-icon right">
                                                    <input name="external[{{$key}}][]" type="text"
                                                           class="form-control input-sm mb-15"
                                                           value="{{$output_member->external}}" {{$disabled}} />
                                                </div>
                                            </div>
                                            <div class="internal-member-wrapper">
                                                <label for="nidn[{{$key}}][]"
                                                       class="col-sm-4 col-md-3 control-label">Nama Penulis</label>
                                                <div class="col-sm-6 input-icon right">
                                                    <input name="nidn_display[{{$key}}][]" type="text"
                                                           class="input-member form-control input-sm mb-15"
                                                           value="{{$output_member->nidn_display}}" {{$disabled}} />
                                                    <input name="nidn[{{$key}}][]" type="text" class="input-value"
                                                           hidden="hidden"
                                                           value="{{$output_member->nidn}}"/>
                                                </div>
                                            </div>
                                            @if($disabled === null)
                                                <div class="col-sm-1">
                                                    <a href="#"
                                                       class="remove-output-member btn btn-sm btn-danger btn-stroke">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if($disabled === null)
                                    <div class="clearfix"></div>
                                    <label class="control-label col-sm-4 col-md-3">Tambah Penulis</label>
                                    <div class="col-sm-1">
                                        <a href="#" class="add-output-member-button btn btn-sm btn-success btn-stroke">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                @endif

                                @if($upd_mode !== 'approve' && $status_code !== 'PS' && $disabled == null)
                                    <div class="clearfix"></div>
                                    <hr/>
                                    <label class="control-label col-sm-4 col-md-3">Unggah Luaran</label>
                                    <div class="col-sm-6 mb-5">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control input-sm" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-success btn-file">
                                                <span class="fileinput-new">Pilih file</span>
                                                <span class="fileinput-exists">Ubah</span>
                                                <input type="file" name="file_name[]"
                                                       value="">
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput">Hapus</a>
                                        </div>
                                        @if($errors->has('file_name.' . $key))
                                            <label class="error" for="file_name[]"
                                                   style="display: inline-block;">
                                                {{ $errors->first('file_name.' . $key) }}
                                            </label>
                                        @endif
                                    </div>

                                    @if($research_output_general->file_name_ori === null)
                                        <div class="remove-output-button-wrapper">
                                            <div class="clearfix"></div>
                                            <label class="control-label col-sm-4 col-md-3">Hapus Luaran</label>
                                            <div class="col-sm-1">
                                                <a href="#" class="remove_field btn btn-sm btn-danger btn-stroke">
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div> <!-- /.form-group -->
                        @endforeach
                    </div>

                    @if($upd_mode !== 'approve' && $status_code !== 'PS' && $disabled == null)
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">

                        <div class="clearfix"></div>
                        <div class="form-footer">
                            <div class="col-sm-offset-4 col-md-offset-3">
                                <a id="add-research-general" href="#"
                                   class="add-research-general-button btn btn-success btn-stroke btn-slideright"><i
                                            class="fa fa-plus"></i></a>
                                <a href="{{url($deleteUrl)}}" class="btn btn-teal btn-slideright">Kembali</a>
                                <button type="submit" class="btn btn-success btn-slideright submit">Simpan</button>
                            </div><!-- /.col-sm-offset-3 -->
                        </div><!-- /.form-footer -->
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>