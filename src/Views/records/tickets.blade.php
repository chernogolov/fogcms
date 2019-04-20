<div class="card-header">
    <span id="head-image">
        @if(isset( $node->image) && $node->image != '')
            <img src="/imagecache/original/{{ $node->image }}" class="img-responsive float-left image-h-24">&nbsp;
        @endif
        {{ $node->name }} <span class="text-muted" title="{{__('Items')}}">({{ $records->total() }})</span>
    </span>
    @if(!isset($node) || $node->is_summary === 0 && isset($edit))
        @if(isset($node->id ))
            <a href="{{ route('regs') . '/new/' .  $node->id}}" class="float-right ajax"><span class="mdi mdi-plus-box"></span>&nbsp;{{__('Add')}}</a>
        @endif
    @endif
</div>
<div class="card-body">
    @include('fogcms::records.filters')
    <form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data" action="{{ route('reg_records', ['id' => $node->id]) }}">
        {{ csrf_field() }}
        <div class="table-container table-responsive">
            <table class="table items-table">
                <tr>
                    <th>
                        <input type="checkbox" class="check_all">
                    </th>
                    <th></th>
                    @foreach($params['fields'] as $key => $field)
                        @if(isset($field->filter_template))
                            @php $field = (array) $field @endphp
                            @php $field['params'] = (array) $params @endphp
                            @php $field['key'] = $key @endphp
                            @include($field['filter_template'], @$field)
                        @endif
                    @endforeach
                </tr>
                @foreach ($records as $record)
                    <tr
                        id="item_{{$record->id}}" class="@if($record->status == 1) danger @elseif ($record->status == 2) warning @elseif ($record->status == 3) success @elseif ($record->status == 4) info @endif"
                       >
                        <td>
                            <input type="checkbox" class="for_check" name="check[{{ $record->id }}]" form="editForm">
                        </td>
                        <td>
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mdi mdi-settings">&nbsp;</span><span class="caret"></span>
                                    </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a data-sid="1" data-id="{{$record->id}}" class="dropdown-item change_status" @if($record->status != 1) href="{{ route('change_status', ['rid' => $record->id]) }}" @endif>{{__('New')}}</a>
                                        <a data-sid="2" data-id="{{$record->id}}" class="dropdown-item change_status" @if($record->status != 2) href="{{ route('change_status', ['rid' => $record->id]) }}" @endif>{{__('In work')}}</a>
                                        <a data-sid="3" data-id="{{$record->id}}" class="dropdown-item change_status" @if($record->status != 3) href="{{ route('change_status', ['rid' => $record->id]) }}" @endif>{{__('Success')}}</a>
                                        @isset($edit)
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item ajax" href="{{ route('edit_record', ['id' => $reg_id, 'rid' => $record->id]  )}}">{{__('Edit')}}</a>
                                        @endisset
                                        @if(isset($node->print_template) && $node->print_template!='')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" target="_blank" href="{{ route('document',  ['id' => $reg_id, 'rid' => $record->id]) }}" title=""><span class="mdi mdi-file"></span>&nbsp;{{__('Download ')}}</a>
                                        @endisset
                                      </div>
                                </div>
                            </div>
                        </td>
                        @if(is_array($params['fields']))
                            @foreach($params['fields'] as $key => $field)
                                <td>
                                    <a href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " id="{{$key}}_{{$record->id}}" title="Просмотр" class="selectable ajax" data-id="{{$record->id}}">
                                        @if(isset($field->type))
                                            @switch($field->type)
                                            @case('image')
                                            @if(!empty($record->$key))
                                                <img src="/imagecache/small/{{ $record->$key }}" class="img-responsive">
                                            @endif
                                            @break
                                            @case('digit')
                                                {{ $record->$key / 100 }}
                                            @break
                                            @case('status')
                                            @if($record->$key == 1)
                                                {{__('New')}}
                                            @elseif($record->$key == 2)
                                                {{__('In work')}}
                                            @elseif($record->$key == 3)
                                                {{__('Completed')}}
                                            @elseif($record->$key == 4)
                                                {{__('Closed by author')}}
                                            @endif
                                            @break
                                            @default
                                                @isset($record->$key){{ str_limit(strip_tags($record->$key), 50) }}@endisset
                                            @endswitch
                                        @endif
                                    </a>
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="row">
            <div class="col-sm-8 text-right">
                {{ $records->links() }}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4 col-xs-2">
                @isset($delete)<button type="submit" data-destination="records" data-btn="delete" class="btn btn-default delete_records submit" id="delete-btn" name="delete" value="true" onclick="return confirm('{{__('Confirm delete')}}?');" form="editForm"><span class="mdi mdi-trash-can"></span>&nbsp;{{__('Delete')}}</button>@endisset
            </div>
            <div class="col-sm-2 col-xs-10 text-right">
                @if(count($records)>0)
                    <button type="button" data-destination="records" class="btn btn-default " id="export" data-id="{{ $node->id }}" form="editForm"><span class="mdi mdi-database-export"></span><span class="hidden-xs">&nbsp;{{__('Export')}}</span></button>
                @endif
            </div>
            <div class="col-sm-6 col-xs-12">
                <br class="d-block d-sm-none">
                <div class="row">
                    <div class="col-sm-8 col-xs-10">
                        <input type="file" name="import_file" class="form-control " id="inputName" form="editForm"
                               accept=".xls, .xlsx, .ods, .csv">
                    </div>
                    <div class="col-sm-4 col-xs-2">
                        <button type="submit" data-destination="records" data-btn="import" name="import" value="excel" class="btn btn-default float-right submit" form="editForm" id="import-btn"><span class="mdi mdi-database-import"></span><span class="hidden-xs">&nbsp;{{__('Import')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        @isset($edit)
        <br>
        <div class="row">
            <div class="col-sm-12">
                <a data-toggle="collapse" data-toggle="collapse" data-target="#collapseOptions" aria-expanded="false" aria-controls="collapseOptions" class="float-right btn btn-sm btn-light">
                    {{__('Additional')}}
                </a>
            </div>
            <div class="col-sm-12 collapse" id="collapseOptions">
                <br>
                <div class="row">
                    <div class="col-sm-6 destination">
                        <select class="copy-records" multiple="multiple" name="destination[]" form="editForm">
                                @foreach($nodes as $n)
                                    @if($n->id == $node->id)
                                        <option value="{{ $n->id }}" selected>@for ($i = 0; $i < $n->depth; $i++)&nbsp;&nbsp;@endfor{{ $n->name }}</option>
                                    @else
                                        <option value="{{ $n->id }}" @if($n->is_summary === "1") disabled @endif @if($n->type !== "tickets") disabled @endif>
                                            @for ($i = 0; $i < $n->depth; $i++)&nbsp;&nbsp;@endfor{{ $n->name }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <label>{{__('Notificate')}}&nbsp;
                        <input type="checkbox" name="notificate" form="editForm" value="true"></label>
                    </div>
                    <div class="col-sm-2 col-xs-12">
                        <div class="btn-group float-right">
                            <button type="button" data-destination="records" data-btn="copy" class="btn btn-default btn-sm copy_records submit" id="copy-btn" name="copy" value="true" onclick="return confirm('{{__('Confirm copy')}}?');" form="editForm">
                                {{__('Copy')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </form>
</div>
<script>
    $(document).ready(function() {
        $(".copy-records").multipleSelect({
            filter: true
        });
    });
</script>
