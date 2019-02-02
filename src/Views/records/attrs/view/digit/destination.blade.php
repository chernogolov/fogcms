<div class="row">
    <div class="col-sm-4">
        <label for="multiple-selected">{{ $attr->title }} @if($attr->is_required == 1)<strong class="text-danger" style="font-size: 24px;">*</strong>@endif</label>
    </div>
    <div class="col-sm-8">
        @if(isset($attr->values))
            @foreach($attr->values as $rnode)
                @php $vls[] = $rnode->value @endphp
            @endforeach
        @endif
        @foreach($vls as $item)
            {{ $attr->data->keyBy('id')[$item]->name }}<br>
        @endforeach
    </div>
</div>
<br>