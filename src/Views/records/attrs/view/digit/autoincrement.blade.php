<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-4">
        @if(!empty($attr->values))
            @foreach($attr->values as $v)
                {{ $v->value }}
            @endforeach
        @endif
    </div>
</div>
<br>