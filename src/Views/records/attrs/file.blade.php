<div class="row">
    <div class="col-sm-3">
        <label class="control-label">{{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-9">
            <div class="row">
                @if(isset($attr->values))
                    @foreach($attr->values as $v)
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="alert">
                                    <div class="" id="file_{{ $v->id }}">
                                        <a href="{{ $v->value }}" target="_blank" title="скачать">
                                            {{ $v->value }}
                                        </a>
                                        @if(isset($access->edit) && intval($access->edit) === 1)
                                        <div class="close" id="{{ $v->id }}">&times</div>
                                        @endif
                                        <input type="hidden" name="attr[{{ $attr->name }}][save][]" value="{{ $v->id }}">

                                        @if(isset($attr->mode) && $attr->mode == 'new')
                                            <input type="hidden" name="attr[{{ $attr->name }}][type]" value="file">
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
                    <input type="hidden" name="attr[{{ $attr->name }}][type]" value="file">
                    <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
                    <input type="file" multiple="multiple" name="attr[{{ $attr->name }}][value][]" class="form-control" id="inputName" placeholder="выберете изображение"
                           accept="" @if($attr->is_required == 1 && empty($attr->values)) required="required" @endif>
                </div>
            </div>
            <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
            @if ($errors->has('attr.'.$attr->name.'.value'))
                <span class="text-danger">
                    <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
                </span>
            @endif
    </div>
</div>
<br>