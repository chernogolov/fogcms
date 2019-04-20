@foreach ($records as $record)
    <option value="{{$record->id}}">
        @foreach($record as $key => $value)
            @if(in_array($key, $attrs))
                {{$value}}&nbsp;
            @endif
        @endforeach
    </option>
@endforeach