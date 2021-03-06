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
                                        <select name="filters[{{$name}}][{{$i}}][]" class="form-control" form="modalForm">
                                            <option value="=" @if($filter[1] == '=') selected @endif>=</option>
                                            <option value="!=" @if($filter[1] == '!=') selected @endif>!=</option>
                                        </select>
                                        <select name="filters[{{$name}}][{{$i}}][]" class="form-control" form="modalForm">
                                            @foreach($data as $item)
                                                <option value="{{$item->id}}" @if($filter[2] == $item->id) selected @endif>{{$item->value}}</option>
                                            @endforeach
                                        </select>
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
                    <div class="col-sm-3 col-xs-4">
                        {{$title}}
                        <input type="hidden"  name="filters[{{$name}}][{{$i}}][]" value="{{$name}}" form="modalForm">
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <select name="filters[{{$name}}][{{$i}}][]" class="form-control" form="modalForm">
                            <option selected disabled></option>
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                        </select>
                    </div>
                    <div class="col-sm-5 col-xs-6">
                        <select name="filters[{{$name}}][{{$i}}][]" class="form-control" form="modalForm">
                            <option selected disabled></option>
                            @foreach($data as $item)
                                <option value="{{$item->id}}">{{$item->value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                <button type="submit" data-destination="records" class="btn btn-primary modal_form submit" form="modalForm">{{__('Filter')}}&nbsp;<span class="mdi mdi-filter"></span></button>
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