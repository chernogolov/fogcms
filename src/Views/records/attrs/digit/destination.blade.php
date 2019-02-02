<div class="form-group row row destination">
    <label class="col-sm-4">{{ $attr->title }} @if($attr->is_required == 1)<strong class="text-danger" style="font-size: 24px;">*</strong>@endif</label>
    <div class="col-sm-8">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">

        <select style="visibility: hidden" multiple="multiple" id="{{$attr->name}}-multiple" size="{{count($attr->data)}}" name="attr[{{ $attr->name }}][value][]" @if($attr->is_required == 1) required="required"@endif>
            @if(isset($attr->values))
                @foreach($attr->values as $rnode)
                    {!! $vls[] = $rnode->value !!}
                @endforeach
            @endif
            @foreach($attr->data as $item)
                @if(!empty($vls))
                    <option value="{{ $item->id }}" @if(in_array($item->id, $vls)) selected @endif @if($item->is_summary === "1") disabled @endif>
                @else
                    <option value="{{ $item->id }}" @if($item->is_summary === "1") disabled @endif>
                @endif
                @for ($i = 0; $i < $item->depth; $i++)&nbsp;&nbsp;@endfor{{ $item->name }}
                </option>
            @endforeach
        </select>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.$attr->name.value'))
            <span class="text-dander">
                <strong>{{ $errors->first('attr.$attr->name.value') }}</strong>
            </span>
        @endif
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#{{$attr->name}}-multiple").multipleSelect({
            filter: true
        });
    });
</script>
