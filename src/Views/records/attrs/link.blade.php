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
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <input autocomplete="off"
                   type="text"
                   class="search-link sl sl-l"
                   data-id="{{$attr->meta}}"
                   id='link-{{$attr->meta}}'
                   list="list-{{$attr->meta}}"
                   placeholder="поиск по: {{implode(', ',array_values($attr->data))}}" style="">
          </div>
          @if(isset($attr->values) && count($attr->values)>0)
              <select class="sl sl-r" data-id="{{$attr->meta}}" name="attr[{{ $attr->name }}][value]" id="list-{{$attr->meta}}" @if($attr->is_required == 1) required="required" @endif>
                  @foreach($attr->values as $v)
                      <option value="{{$v->value}}" selected>
                          @foreach($v->fields as $key => $field)
                              @isset($v->data[$key]){{ $v->data[$key]}}&nbsp;@endisset
                          @endforeach
                      </option>
                  @endforeach
              </select>
          @else
              <select class="sl sl-r clear" data-id="{{$attr->meta}}" name="attr[{{ $attr->name }}][value]" id="list-{{$attr->meta}}" @if($attr->is_required == 1) required="required" @endif></select>
          @endif
        </div>
        <label id="attr_{{$attr->name}}_value" class="hidden text-danger"></label>
        @if ($errors->has('attr.'.$attr->name.'.value'))
            <span class="text-danger">
                <strong>{{ $errors->first('attr.'.$attr->name.'.value') }}</strong>
            </span>
        @endif
        @if (Gate::allows('view-regs'))
            @if(intval($attr->datalist) == 1)
                <!-- Modal -->
                <div class="modal fade" id="modal-{{$attr->meta}}" tabindex="-1" role="dialog" aria-labelledby="myModal{{$attr->meta}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModal{{$attr->meta}}">{{$attr->title}}&nbsp;:&nbsp;<span></span></h4>
                            </div>
                            <div class="modal-body">
                                <img src="/img/load_min.gif" style="margin: 20px auto;">
                            </div>
                            <div class="modal-footer">
<!--                                    <button type="button" class="btn btn-primary">Создать дубликат</button>-->
                                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-sm btn-default float-right disabled" data-filter=""  data-attr="{{$attr->name}}" data-toggle="modal" data-target="#modal-{{$attr->meta}}" id="similar-{{$attr->meta}}" href="#" style="margin-top: 10px;">
                    Поиск по выбранному значению
                </a>
            @endif
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
                    $.ajax({
                        url: "/search/"+id,
                        type: "POST",
                        data: {},
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

            .on("change", '#list-{{$attr->meta}}', function()
            {
                var address = $('#similar-{{$attr->meta}}').data('attr');
                var filter = {[$('#similar-{{$attr->meta}}').data('attr')] : { 0 :[$('#similar-{{$attr->meta}}').data('attr'), '=', $("#list-{{$attr->meta}} option:selected").val()]}};
                $('#similar-{{$attr->meta}}').removeClass('disabled').data("filter", filter);
                $('#myModal{{$attr->meta}} span').html($("#list-{{$attr->meta}} option:selected").text());
            })
            .on("click", '#similar-{{$attr->meta}}', function()
            {
                var params = {};
                params.template = 'easy';
                params.filters = $(this).data('filter');
                params.nosavefilters = true;
                var destination = 'modal-{{$attr->meta}} .modal-body';
                var url = '/panel/3';
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $.param(params),
                    type: "POST",
                    beforeSend: function() {
                        $('#'+destination+' #head-image').html('<img src="/img/load_min.gif" style="height: 22px;">&nbsp;&nbsp;Загрузка...');
                    },
                    success: function (data) {
                        $('#'+destination).html(data);
                    },
                    error: function (msg) {
                        $('#'+destination).html(msg);
                    }
                });
            })
    });
</script>
