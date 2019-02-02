<div class="modal fade" id="user_idFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{__('User')}} {{__('Filter')}}</h4>
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
                    @php $i = 0 @endphp
                    @if(isset($params['filters']['user_id']))
                    @foreach($params['filters']['user_id'] as $filter)
                    <div class="alert row">
                        <div class="col-sm-5">
                            {{__('User')}}
                            <input type="hidden"  name="filters[user_id][{{$i}}][]" value="records.user_id" form="editForm">
                            <input type="hidden"  name="filters[user_id][{{$i}}][]" value="=" form="editForm">
                        </div>
                        <div class="col-sm-6 col-xs-10">
                            <input type="text" class="form-control" name="filters[user_id][{{$i}}][]" form="editForm" value="{{ $filter[2] }}">
                        </div>
                        <div class="col-sm-1 col-xs-2">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" title="{{__('Remove filter')}}">&times;</button>
                        </div>
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @endif
                </div>
                @if(!isset($params['filters']['user_id']))
                <div class="row">
                    <div class="col-sm-5 col-xs-4">
                        {{__('User')}}
                        <input type="hidden"  name="filters[user_id][{{$i}}][]" value="records.user_id" form="editForm">
                        <input type="hidden"  name="filters[user_id][{{$i}}][]" value="=" form="editForm">
                    </div>
                    <div class="col-sm-6 col-xs-8">
                        <input type="text" class="form-control" name="filters[user_id][{{$i}}][]" form="editForm">
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                <button type="submit" data-destination="records" class="btn btn-primary submit" form="editForm">{{__('Filter')}}&nbsp;<span class="mdi mdi-filter"></span></button>
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