<div class="row">
    <div class="col-lg-5">
        <label class="control-label mb-2 text-muted">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-lg-7">
        @if(isset($attr->values->value))
            {!! $attr->values->value !!}
        @endif
    </div>
</div>
<br>