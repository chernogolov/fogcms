<div class="modal fade" id="statusFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{__('Status')}} {{__('Filter')}}</h4>
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
                        @if(isset($params['filters']['status']))
                            @foreach($params['filters']['status'] as $filter)
                                <div class="alert row">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">{{__('Status')}}</label>
                                        </div>
                                        <input type="hidden"  name="filters[status][{{$i}}][]" value="records.status" form="modalForm">
                                        <select name="filters[status][{{$i}}][]" class="form-control" form="modalForm">
                                            <option value="=" @if($filter[1] == '=') selected @endif>=</option>
                                            <option value="!=" @if($filter[1] == '!=') selected @endif>!=</option>
                                        </select>
                                        <select name="filters[status][{{$i}}][]" class="form-control" form="modalForm">
                                            <option value="1" @if($filter[2] == '1') selected @endif>Новая</option>
                                            <option value="2" @if($filter[2] == '2') selected @endif>В работе</option>
                                            <option value="3" @if($filter[2] == '3') selected @endif>Выполнено</option>
                                            <option value="4" @if($filter[2] == '4') selected @endif>Закрыто автором</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="alert" aria-hidden="true" title="{{__('Remove filter')}}">&times;</button>
                                        </div>
                                    </div>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
                {{--@if(!isset($params['filters']['status']))--}}
                <div class="row">
                    <div class="col-sm-3 col-xs-4">
                        {{__('Status')}}
                        <input type="hidden"  name="filters[status][{{$i}}][]" value="records.status" form="modalForm">
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <select name="filters[status][{{$i}}][]" class="form-control" form="modalForm">
                            <option selected disabled></option>
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                        </select>
                    </div>
                    <div class="col-sm-5 col-xs-8">
                        <select name="filters[status][{{$i}}][]" class="form-control" form="modalForm">
                            <option selected disabled></option>
                            <option value="1">{{__('New')}}</option>
                            <option value="2">{{__('In work')}}</option>
                            <option value="3">{{__('Completed')}}</option>
                            <option value="4">{{__('Closed by author')}}</option>
                        </select>
                    </div>
                </div>
                {{--@endif--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                <button type="submit" data-destination="records" class="btn btn-primary modal_form submit" form="modalForm">{{__('Filter')}}&nbsp;<span class="mdi mdi-filter"></span></button>
            </div>
        </div>
    </div>
</div>



<th style="min-width: 70px;">
    @if(isset($field['icon'])) <span class="{{ $field['icon'] }}" title="{{ $field['title'] }}"></span> @else {{ $field['title'] }} @endif
    @if(isset($params['orderBy']['field']) && isset($params['orderBy']['type']) && $params['orderBy']['field'] == $key)
    @if($params['orderBy']['type'] == 'asc')
    <a class="text-danger ajax"  href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => $key, 'type' => 'desc']]) }}"><span class="mdi mdi-sort-ascending"></span></a>
    @elseif($params['orderBy']['type'] == 'desc')
    <a class="text-danger ajax"  href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => $key, 'type' => 'asc']]) }}"><span class="mdi mdi-sort-descending"></span></a>
    @endif
    @else
    <a class="text-muted ajax" href="{{ route('reg_records', ['id' => $reg_id, 'orderBy' => ['field' => $key, 'type' => 'asc']]) }}" title="{{__('Sort')}}"><span class="mdi mdi-sort-ascending"></span></a>
    @endif
    @if(isset($params['filters'][$key]))
    <a class="text-danger" data-toggle="modal" data-target="#{!! $key !!}Filter"><span class="mdi mdi-filter"></span></a>
    @else
    <a class="text-muted" data-toggle="modal" data-target="#{!! $key !!}Filter"><span class="mdi mdi-filter"></span></a>
    @endif
</th>