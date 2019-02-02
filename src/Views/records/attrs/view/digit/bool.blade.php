<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-8">
        @if(isset($attr->values->value) && $attr->values->value == 1)
            Да
        @else
            Нет
        @endif>
    </div>
</div>
<br>