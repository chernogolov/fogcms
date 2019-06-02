<div class="row">
    <div class="col-lg-5">
        <label class="control-label text-muted mb-2">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-lg-7">
        @if(isset($attr->values->value))
            {{ $attr->values->value }}
        @endif
    </div>
</div>
<br>