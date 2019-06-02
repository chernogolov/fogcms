<div class="row">
    <div class="col-lg-5">
        <label class="control-label text-muted mb-2">
            {{ $attr->title }}
        </label>
    </div>
    <div class="col-lg-7">
        @if(isset($attr->values))
            @foreach($attr->values as $value)
                @foreach($value->fields as $key => $field)
                    @isset($value->data[$key]){{ $value->data[$key]}}&nbsp;@endisset
                    @isset($value->data['address.fulladdress'])({{$value->data['address.fulladdress']}} {{__('room')}} {{$value->data['room']}})@endisset
                @endforeach
            @endforeach
        @endif
    </div>
</div>
<br>