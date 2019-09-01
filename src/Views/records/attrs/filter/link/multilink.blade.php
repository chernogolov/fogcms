<div class="modal fade" id="{{$name}}Filter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">"{{ $title }}" {{__('Filter')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h5>
                            {{__('Used filters')}}
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @php $i = 0 @endphp
                        @if(isset($params['filters'][$name]))
                            @foreach($params['filters'][$name] as $filter)
                                <div class="alert row">
                                    <div class="input-group">
                                        <input type="hidden"  name="filters[{{$name}}][{{$i}}][]" value="{{$name}}" form="modalForm">
                                        <input type="hidden"  name="filters[{{$name}}][{{$i}}][]" value="=" form="modalForm">
                                        <select name="filters[{{$name}}][{{$i}}][]" class="custom-select" form="modalForm">
                                            <option value="=" @if($filter[1] == '=') selected @endif>=</option>
                                            <option value=">" @if($filter[1] == '>') selected @endif>&gt;</option>
                                            <option value="<" @if($filter[1] == '<') selected @endif>&lt;</option>
                                        </select>
                                        <input type="number" class="form-control " name="filters[{{$name}}][{{$i}}][]" value="{{ $filter[2] }}"  form="modalForm">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="alert" aria-hidden="true" title="{{__('Remove filter')}}">&times;</button>
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="filters[{{$name}}][{{$i}}][]" value="{{$name}}" form="modalForm">
                        <input type="hidden" name="filters[{{$name}}][{{$i}}][]" value="=" form="modalForm">
                        <div class="input-group">
                            <input autocomplete="off"
                                   type="text"
                                   class="search-link sl sl-l"
                                   data-id="{{$meta}}"
                                   id='link-{{$meta}}'
                                   list="list-{{$meta}}"
                                   placeholder="{{__('Search')}}: {{implode(', ',array_values($data))}}"
                                   form="modalForm"
                                    >
                            <div class="input-group-append">
                                @if(isset($values) && count($values)>0)
                                    @foreach($values as $v)
                                        <input type="hidden" name="attr[{{ $name }}][save][]" value="{{ $v->id }}">
                                        <input type="hidden" name="attr[{{ $name }}][id]" value="{{ $v->id }}">
                                    @endforeach
                                    <select class="sl sl-r" data-id="{{$meta}}" name="filters[{{ $name }}][{{$i}}][]" id="list-{{$meta}}" form="modalForm">
                                        @foreach($values as $v)
                                            <option value="{{$v->value}}" selected>
                                                @foreach($v->fields as $key => $field)
                                                    @isset($v->data[$key]){{ $v->data[$key]}}&nbsp;@endisset
                                                @endforeach
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="sl sl-r clear" data-id="{{$meta}}" name="filters[{{ $name }}][{{$i}}][]" id="list-{{$meta}}" form="modalForm"></select>
                                @endif
                            </div>
                        </div>
                        <label id="attr_{{$name}}_value" class="hidden text-danger"></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" form="modalForm">{{__('Close')}}</button>
                <button type="submit" data-destination="records"  role="submit" name="filter" class="btn btn-primary submit" form="modalForm">{{__('Filter')}}&nbsp;<span class="mdi mdi-filter"></span></button>
            </div>
        </div>
    </div>
</div>
<th style="min-width: 70px;">
    {{ $title }}
    @if(isset($params['orderBy']['field']) && isset($params['orderBy']['attr']) && $params['orderBy']['attr'] == $name)
        @if($params['orderBy']['type'] == 'asc')
            <a class="text-danger ajax"  href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => 'value', 'type' => 'desc', 'attr' => $name]]) }}"><span class="mdi mdi-sort-ascending"></span></a>
        @elseif($params['orderBy']['type'] == 'desc')
            <a class="text-danger ajax"  href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => 'value', 'type' => 'asc', 'attr' => $name]]) }}"><span class="mdi mdi-sort-descending"></span></a>
        @endif
    @else
        <a class="text-muted ajax" href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => 'value', 'type' => 'asc', 'attr' => $name]]) }}" title="{{__('Sort')}}"><span class="mdi mdi-sort-ascending"></span></a>
    @endif
    @if(isset($params['filters'][$name]))
        <a class="text-danger" data-toggle="modal" data-target="#{!! $name !!}Filter"><span class="mdi mdi-filter"></span></a>
    @else
        <a class="text-muted" data-toggle="modal" data-target="#{!! $name !!}Filter"><span class="mdi mdi-filter"></span></a>
    @endif
</th>
<script>
    $(function()
    {
        $( document ).ready(function() {
            if($('#list-{{$meta}}').hasClass('clear'))
            {
                var id = $('#list-{{$meta}}').data('id');
                var destination = 'list-'+id;
                $.ajax({
                    url: "/search/"+id,
                    type: "POST",
                    data: {},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#'+destination).html('<option value="" selected disabled hidden>&nbsp;&nbsp;{{__('Loading')}}...</option>');
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
        });
    });
</script>

