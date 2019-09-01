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
                {{ $value->value }}
            @endforeach
        @else
            {{__('Automatic fill')}}
            <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
            <input type="hidden" name="attr[{{ $attr->name }}][value]" value="autoincrement">
        @endif
    </div>
</div>
<br>