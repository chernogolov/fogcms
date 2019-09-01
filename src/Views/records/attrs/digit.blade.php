<div class="row">
    <div class="col-sm-3">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
            <strong class="text-danger">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-9">
        @if(isset($attr->values) && count($attr->values)>0)
            @foreach($attr->values as $value)
                <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
                @if(isset($value->id))
                    <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $value->id }}">
                @endif
                <input type="number" step="any" class="form-control" @if(isset($value->value)) value="{{ $value->value }}" @endif name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
            @endforeach
        @else
            <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
            <input type="number" step="any" class="form-control" name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
        @endif
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
                    <span class="text-danger">
                        <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
                    </span>
        @endif
    </div>
</div>
<br>