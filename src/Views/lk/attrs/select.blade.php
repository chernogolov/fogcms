<div class="row mb-2">
    <div class="col-lg-4">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger">*</strong>
            @endif
        </label>
    </div>
    <div class="col-lg-8">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
        @if(isset($attr->values))
            @foreach($attr->values as $value)
                <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $value->id }}">
            @endforeach
        @endif
        <select class="form-control" name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
            @php $vls=[] @endphp
            @if(isset($attr->values))
                @foreach($attr->values as $value)
                    {{ $vls[] = $value->value }}
                @endforeach
            @else
                <option value="" disabled selected>Выберите из списка</option>
            @endif
            @foreach($attr->data as $item)
                <option value="{{ $item->id }}" @if(isset($vls) && in_array($item->id, $vls)) selected @endif>{{ $item->value }}</option>
            @endforeach
        </select>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
                        <span class="text-danger">
                            <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
                        </span>
        @endif
    </div>
</div>
