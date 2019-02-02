<div class="row">
    <div class="col-sm-4">
        <label class="control-label">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-sm-7">
        @if(isset($attr->values))
            @foreach($attr->values as $value)
                @if (Gate::allows('view-regs'))
                <a href="{{ route('edit_record', ['id' => $attr->meta, 'rid' => $value->value]  )}}" target="_blank" class="ajax">
                    @foreach($value->fields as $key => $field)
                        @isset($value->data[$key]){{ $value->data[$key]}}&nbsp;@endisset
                    @endforeach
                </a>
                @else
                    @foreach($value->fields as $key => $field)
                        @isset($value->data[$key]){{ $value->data[$key]}}&nbsp;@endisset
                    @endforeach
                @endif
            @endforeach
        @endif
    </div>
</div>
<br>