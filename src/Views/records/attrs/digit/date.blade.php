<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
            <strong class="text-danger" style="font-size: 24px;">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-4">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
        @if(isset($attr->values->id))
        <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $attr->values->id }}">
        @endif
        <input type="date" class="form-control" @if(isset($attr->values->value)) value="{{ $attr->values->value }}" @endif name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.$attr->name.value'))
                    <span class="text-dander">
                        <strong>{{ $errors->first('attr.$attr->name.value') }}</strong>
                    </span>
        @endif
    </div>
</div>
<br>