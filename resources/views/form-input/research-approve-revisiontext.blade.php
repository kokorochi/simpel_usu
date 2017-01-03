<div class="form-group">
    <label for="title" class="col-sm-4 col-md-3 control-label">Alasan Perbaikan</label>
    <div class="col-sm-7">
        <textarea name="revision_text" class="form-control input-sm"
                  rows="3" {{$upd_mode === 'output' ? "disabled" : ""}}>{{$research_output_revision->revision_text}}</textarea>
        @if($errors->has('revision_text'))
            <label class="error" for="revision_text" style="display: inline-block;">
                {{ $errors->first('revision_text') }}
            </label>
        @endif
    </div>
</div>