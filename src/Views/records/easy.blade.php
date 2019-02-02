<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data" action="{{ route('reg_records', ['id' => $node->id]) }}">
    {{ csrf_field() }}
    <div class="table-container">
        <table class="table table-responsive items-table">
            <tr>
<!--                <th></th>-->
                @foreach($params['fields'] as $key => $field)
                @if(isset($field->title))
                    <th>{{$field->title}}</th>
                @endif
                @endforeach
                <th></th>
            </tr>
            @foreach ($records as $record)
            <tr>
<!--                <td>-->
<!--                    <input type="checkbox" class="for_check" name="delete[{{ $record->id }}]" form="editForm">-->
<!--                </td>-->
                @if(is_array($params['fields']))
                    @foreach($params['fields'] as $key => $field)
                        <td>
                            <a title="откроется в новом окне" target="_blank" href="{{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}}">
                                @if(isset($field->type))
                                @switch($field->type)
                                @case('date')
                                {{ $record->$key }}
                                @break
                                @case('image')
                                @if(!empty($record->$key))
                                <img src="/imagecache/small/{{ $record->$key }}" class="img-responsive">
                                @endif
                                @break

                                @case('status')
                                @if($record->$key == 1)
                                Новый
                                @elseif($record->$key == 2)
                                В работе
                                @elseif($record->$key == 3)
                                Выполнен
                                @elseif($record->$key == 4)
                                Закрыто автором
                                @endif
                                @break
                                @default
                                {{ str_limit($record->$key, 50) }}
                                @endswitch
                                @endif
                            </a>
                        </td>
                    @endforeach
                @endif
                <td>
                    <a href="/new/{{$reg_id}}/{{ $record->id }}">
                        <span class="mdi mdi-duplicate" title="создать на основе"></span>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="row">
        <div class="col-sm-8 text-right">
            {{ $records->links() }}
        </div>
    </div>
</form>
