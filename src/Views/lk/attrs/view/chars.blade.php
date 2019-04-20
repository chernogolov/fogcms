<div class="row">
    <div class="col-sm-5 pb-lg-2">
        <label class="control-label mb-2 text-muted">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-7">
        @if(isset($attr->values->value))
            {{ $attr->values->value }}
        @endif
    </div>
</div>
<br>