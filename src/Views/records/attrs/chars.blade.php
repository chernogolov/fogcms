<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger" style="font-size: 24px;">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-8">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
        @if(isset($attr->values) && !empty($attr->values))
            <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $attr->values->id }}">
        @endif
        <input type="text" name="attr[{{ $attr->name }}][value]" class="form-control" list="l_{{ $attr->name }}" @if(isset($attr->values)) value="{{ $attr->values->value }}" @endif  @if($attr->is_required == 1) required="required" @endif>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
            <span class="text-danger">
                <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
            </span>
        @endif
        @if(strlen($attr->datalist)>1)
            @php $list = explode(PHP_EOL, trim($attr->datalist)) @endphp
            <datalist id="l_{{ $attr->name }}">
                @foreach($list as $item)
                    <option value="{{ trim($item) }}"></option>
                @endforeach
            </datalist>
        @endif
    </div>
</div>
<br>