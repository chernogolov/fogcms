<div class="row mb-2">
    <div class="col-lg-4">
        <label class="control-label">
            {{ $attr->title }}
            @if($attr->is_required == 1)
                <strong class="text-danger">*</strong>
                <sup>
                    <span class="mdi mdi-question-sign" data-toggle="tooltip" data-placement="right" title="Начните вводить значение в левом поле, а затем выберете его в правом"></span>
                </sup>
            @endif
        </label>
    </div>
    <div class="col-lg-8">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
        @isset($accounts)
            <select class="form-control" name="attr[{{ $attr->name }}][value]" required="required">
                @foreach($accounts as $item)
                    <option value="{{ $item->id }}" @if(isset($current_account) && $current_account['id'] == $item->id) selected @endif>{{ $item->address }}&nbsp;{{__('room')}}&nbsp;{{ $item->room }}</option>
                @endforeach
            </select>
        @endisset
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
            <span class="text-danger">
                <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
            </span>
        @endif
    </div>
</div>
