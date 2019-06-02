<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
            <strong class="text-danger">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-4">
        {{__('Automatic fill')}}
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
        <input type="hidden" name="attr[{{ $attr->name }}][value]" value="autoincrement">
    </div>
</div>
<br>