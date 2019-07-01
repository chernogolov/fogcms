<div class="card-header">
    <span id="head-image">
        @if(isset( $node->image) && $node->image != '')
            <img src="/imagecache/original/{{ $node->image }}" class="img-responsive float-left image-h-24">&nbsp;
        @endif
        {{ $node->name }}
        <span class="text-muted" title="{{__('Items')}}">({{ $records->total() }})</span>
    </span>
    @if(!isset($node) || $node->is_summary === 0)
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
                    @foreach($params['fields'] as $key => $field)
                        @if(isset($field->filter_template))
                            @php $field = (array) $field @endphp
                            @php $field['params'] = (array) $params @endphp
                            @php $field['key'] = $key @endphp
                            @include($field['filter_template'], @$field)
                        @endif
                    @endforeach
                    @if(isset($node->print_template) && $node->print_template!='')
                        <th>
                        </th>
                    @endisset
                </tr>
                @php $tabindex = 0 @endphp
                    @foreach ($records as $record)
                    @php $tabindex++ @endphp
                    <tr>
                        <td>
                            <input type="checkbox" class="for_check" name="check[{{ $record->id }}]" form="editForm">
                        </td>
                        @foreach($params['fields'] as $key => $field)
                            <td>
                                @switch($field->type)
                                    @case('rating')
                                        <input type="number" id="rating-{{$record->id}}" tabindex="{{$tabindex}}" class="form-control change_rating" data-id="{{$record->id}}" value="{{$record->rating}}" style="width:65px;">
                                    @break
                                    @case('status')
                                    @if($record->$key == 1)
                                        <a id="{{$record->id}}" data-sid="0" data-id="{{$record->id}}" class="on_off" href="{{ route('onoff', ['rid' => $record->id, 'sid' => 0]) }}">
                                            <div id="onoff_{{$record->id}}">
                                                <img src="/img/on.png" class="off" title="{{__('On')}}">
                                            </div>
                                        </a>
                                    @elseif($record->$key == 0)
                                        <a id="{{$record->id}}" data-sid="1" data-id="{{$record->id}}" class="on_off" href="{{ route('onoff', ['rid' => $record->id, 'sid' => 1]) }}">
                                            <div id="onoff_{{$record->id}}">
                                                <img src="/img/off.png" title="{{__('Off')}}">
                                            </div>
                                        </a>
                                    @endif
                                    @break
                                @endswitch

                                <a href=" {{ route('edit_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " id="{{$key}}_{{$record->id}}"  style="display: block;color: #333;text-decoration: none" title="Просмотр" class="selectable ajax" data-id="{{$record->id}}">
                                    @switch($field->type)
                                    @case('date')
                                        {{ date('Y-m-d H:i', $record->$key) }}
                                    @break

                                    @case('image')
                                        @if(!empty($record->$key))
                                            <img src="/imagecache/small/{{ $record->$key }}" class="img-responsive">
                                    @endif
                                    @break
                                    @case('rating')@break
                                    @case('status')@break
                                    @default
                                        @isset($record->$key){{ str_limit(strip_tags($record->$key), 50) }}@endisset
                                    @endswitch
                                </a>
                            </td>
                        @endforeach
                        @if(isset($node->print_template) && $node->print_template!='')
                        <td>
                            <a target="_blank" href="{{ route('document',  ['id' => $reg_id, 'rid' => $record->id]) }}" title="сформировать документ по шаблону"><span class="mdi mdi-file"></span></a>
                        </td>
                        @endisset
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                {{ $records->links() }}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4 col-xs-2">
                @isset($delete)<button type="submit" data-destination="records" data-btn="delete" class="btn btn-default delete_records submit" id="delete-btn" name="delete" value="true" onclick="return confirm('{{__('Confirm delete')}}?');" form="editForm"><span class="mdi mdi-trash-can"></span>&nbsp;{{__('Delete')}}</button>@endisset
            </div>
            <div class="col-sm-2 col-xs-10 text-right">
                <button type="submit" data-destination="records" class="btn btn-default " id="export" data-id="{{ $node->id }}" form="editForm" name="export" value="true" ><span class="mdi mdi-database-export"></span><span class="hidden-xs">&nbsp;{{__('Export')}}</span></button>
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
                    <div class="col-12">
                        <h5>
                            {{__('Copying')}}
                        </h5>
                    </div>
                    <div class="col-sm-6 destination">
                        <select class="copy-records" multiple="multiple" name="destination[]" form="editForm">
                                @foreach($nodes as $n)
                                    @if($n->id == $node->id)
                                        <option value="{{ $n->id }}" selected>@for ($i = 0; $i < $n->depth; $i++)&nbsp;&nbsp;@endfor{{ $n->name }}</option>
                                    @else
                                        <option value="{{ $n->id }}" @if($n->is_summary === "1") disabled @endif @if($n->type !== "data") disabled @endif>
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
                    <div class="col-12">
                    <hr>
                        <h5>
                            {{__('Clear')}}
                        </h5>
                    </div>
                    <div class="col-sm-10 col-12">
                        <label>{{__('Delete all data in this journal: ') . $node->name }}&nbsp;</label>
                    </div>
                    <div class="col-sm-2 col-12">
                        <div class="btn-group float-right">
                            <button type="button" data-destination="records" data-btn="copy" class="btn btn-default btn-sm copy_records submit" id="copy-btn" name="clear" value="{{$node->id}}" onclick="return confirm('{{__('Are you sure? This action cannot be undone! ')}}')" form="editForm">
                                {{__('Clear')}}
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
