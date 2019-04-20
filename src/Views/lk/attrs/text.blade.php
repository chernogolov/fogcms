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
        @if(isset($attr->values) && !empty($attr->values))
            <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $attr->values->id }}">
        @endif

        @php $text = '' @endphp

        @if(isset($attr->values->value))
            @php $text = $attr->values->value @endphp
        @else
            @if (Gate::allows('view-regs'))
                @if(strlen($attr->datalist)>1)
                    @php $text = $attr->datalist @endphp
                @endif
            @endif
        @endif
        <textarea
            class="form-control"
            name="attr[{{ $attr->name }}][value]"
            @if($attr->is_required == 1) required="required" @endif>{{$text}}</textarea>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
            <span class="text-danger">
                <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
            </span>
        @endif
    </div>
</div>
