@if (Gate::allows('view-regs'))
<div class="row">
    <div class="col-sm-3">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger" style="font-size: 24px;">*</strong>
            @endif
        </label>
    </div>
    <div class="col-sm-9 pt-3">
        @if(isset($attr->values->value) && !isset($attr->mode))
            <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
            @if(isset($attr->values->id))
                <input type="hidden" name="attr[{{ $attr->name }}][id]" value="{{ $attr->values->id }}">
            @endif
            <div class="row">
                <div class="col-sm-4">
                    {{ date('Y-m-d H:i', $attr->values->value) }}
                </div>
                <div class="col-sm-4 text-right">
                    {{__('Add')}}
                </div>
                <div class="col-sm-4">
                    <select class="form-control form-control-sm" name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
                    <option value="{{ $attr->values->value }}" selected>0 ч.</option>
                    <option value="{{ 43200 + $attr->values->value }}">12 ч.</option>
                    <option value="{{ 86400 + $attr->values->value }} ">24 ч.</option>
                    <option value="{{ 172800 + $attr->values->value }}">48 ч.</option>
                    <option value="{{ 259200 + $attr->values->value }}">72 ч.</option>
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
                <select class="form-control" name="attr[{{ $attr->name }}][value]" @if($attr->is_required == 1) required="required" @endif>
                    @foreach($attr->data as $key => $day)
                        @php
                            $delta = $day->timestamp - time();
                            $hours = $key * 24;
                        @endphp
                        <option value="{{ $delta }} ">{{$hours}} ч. @if($delta / 86400 != $key) - с учетом выходных ({{$day}}) @endif</option>
                    @endforeach
                </select>

        @endif
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.$attr->name.value'))
                    <span class="text-dander">
                        <strong>{{ $errors->first('attr.$attr->name.value') }}</strong>
                    </span>
        @endif
    </div>
</div>
<br>
@else
    <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
@endif