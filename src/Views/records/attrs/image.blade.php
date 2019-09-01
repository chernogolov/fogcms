<div class="row">
    <div class="col-sm-3">
        <label class="control-label">{{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger">*</strong>
            @endif
        </label>

    </div>
    <input type="hidden" name="attr[{{ $attr->name }}][save][]" value="">
    <div class="col-sm-9">
            <div class="row">
                @if(isset($attr->values))
                    @foreach($attr->values as $v)
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="alert">
                                    <div class="" id="file_{{ $v->id }}">
                                        <a href="/imagecache/original/{{ $v->value }}" target="_blank" title="открыть в новом окне">
                                            <img src="/imagecache/small/{{ $v->value }}" class="img-responsive">
                                        </a>
                                        @if(isset($access->edit) && intval($access->edit) === 1)
                                        <div class="close" id="{{ $v->id }}">&times</div>
                                        @endif
                                        <input type="hidden" name="attr[{{ $attr->name }}][save][]" value="{{ $v->id }}">

                                        @if(isset($attr->mode) && $attr->mode == 'new')
                                            <input type="hidden" name="attr[{{ $attr->name }}][type]" value="img">
                                            <input type="hidden" name="attr[{{ $attr->name }}][value][]" value="{{ $v->value }}">
                                        @endif

                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="background: rgba(0,0,0,1);border: #fff solid 1px;position: absolute;top:-5px;right:5px;">&times;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="hidden" name="attr[{{ $attr->name }}][type]" value="img">
                    <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
                    <input type="file" multiple="multiple" name="attr[{{ $attr->name }}][value][]" class="form-control" id="inputName" placeholder="выберете изображение"
                           accept="image/*" @if($attr->is_required == 1) required="required" @endif>
                </div>
            </div>
    </div>
</div>
<br>