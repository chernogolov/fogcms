<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-7">
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