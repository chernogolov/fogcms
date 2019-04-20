<div class="row">
    <div class="col-lg-5">
        <label class="control-label mb-2 text-muted">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-lg-7">
        @if(isset($attr->values))
            @foreach($attr->values as $value)
                @if(isset($attr->data[$value->value]))
                    {{ $attr->data[$value->value]->value }}
                @endif
            @endforeach
        @endif
    </div>
</div>
<br>