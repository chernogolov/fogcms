<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-4">
        @if(isset($attr->values->value))
            {{ date('Y-m-d H:i', $attr->values->value) }}
        @endif
    </div>
</div>
<br>