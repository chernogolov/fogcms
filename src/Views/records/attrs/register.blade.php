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
        @if(isset($attr->values->id))
        <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $attr->values->id }}">
        @endif
        <div class="row">
            <div class="col-sm-6">
                <input type="number" class="form-control" name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
            </div>
            <div class="col-sm-6">
                <input type="date" class="form-control datepicker" name="attr[{{ $attr->name }}][meta]" value="@php echo date('Y-m-d') @endphp">
            </div>
        </div>
        <br>
        <small class="text-muted">Значения регистра</small>
        <br>
        @foreach($attr->values as $value)
           <small class="btn btn-default btn-sm">{{ $value->value }} : {{ $value->meta }}</small>&nbsp;
        @endforeach
    </div>
</div>
<br>