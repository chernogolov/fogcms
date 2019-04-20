<div class="row">
    <div class="col-sm-4">
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
    <div class="col-sm-8">
        <input type="hidden" name="attr[{{ $attr->name }}][attr_id]" value="{{ $attr->attr_id }}">
            <input autocomplete="off"
                   type="text"
                   class="search-link form-control mb-3"
                   data-id="{{$attr->meta}}"
                   data-limit="100"
                   id='link-{{$attr->meta}}'
                   list="list-{{$attr->meta}}"
                   placeholder="поиск по: {{implode(', ',array_values($attr->data))}}" style="">
              @if(isset($attr->values) && count($attr->values)>0)
                  <select class="form-control" data-vls="{{implode(',', $attr->values->keys()->all())}}" style="height: 500px"  multiple="multiple" data-limit="100" data-id="{{$attr->meta}}" name="attr[{{ $attr->name }}][value][]" id="list-{{$attr->meta}}" @if($attr->is_required == 1) required="required" @endif>
                      @foreach($attr->values as $v)
                          <option value="{{$v->value}}" selected>
                              @foreach($v->fields as $key => $field)
                                  @isset($v->data[$key]){{ $v->data[$key]}}&nbsp;@endisset
                              @endforeach
                          </option>
                      @endforeach
                  </select>
              @else
                  <select class="form-control clear" style="height: 500px" multiple="multiple" data-limit="100" data-id="{{$attr->meta}}" name="attr[{{ $attr->name }}][value][]" id="list-{{$attr->meta}}" @if($attr->is_required == 1) required="required" @endif></select>
              @endif
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
            <span class="text-danger">
                <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
            </span>
        @endif
    </div>
</div>
<br>

<script>
    $(function()
    {

        $( document )
            .ready(function()
            {
                if($('#list-{{$attr->meta}}').hasClass('clear'))
                {
                    var id = $('#list-{{$attr->meta}}').data('id');
                    var destination = 'list-'+id;
                    if($('#list-{{$attr->meta}}').data('limit') != undefined)
                        var limit = $('#list-{{$attr->meta}}').data('limit');
                    else
                        var limit = 20;
                    $.ajax({
                        url: "/search/"+id,
                        type: "POST",
                        data: {limit:limit},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('#'+destination).html('<option value="" selected disabled hidden>&nbsp;&nbsp;Загрузка...</option>');
                        },
                        success: function (data) {
                            $('#'+destination).html('<option value="" selected disabled hidden></option>');
                            $('#'+destination).append(data);
                        },
                        error: function (msg) {
                            $('#'+destination).html(msg);
                        }
                    });
                }
            })
    });
</script>
