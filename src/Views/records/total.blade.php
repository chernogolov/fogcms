<div class="card-header">
    <span id="head-image">
        Сводка
    </span>
</div>
<div class="card-body">
    <div class="row">
        @foreach($nodes as $item)
            <div class="col-sm-4">
                <h4>
                    <a class="ajax" href="{{ route('reg_records', ['id' => $item->id]) }}">{{ $item->name }}</a>
                </h4>
                @if(isset($item->image) && $item->image != '')
                <img src="/imagecache/original/{{ $item->image }}" class="float-left" style="margin: 0 15px 15px 0">
                @else
                <img src="/img/panel/q.png" class="float-left" style="margin: 0 15px 15px 0" title="Загрузите иконку для {{ $item->name }}">
                @endif

                <strong>Тип журнала:</strong>
                    @if($item->is_summary == 1)
                        <p>Подытог</p>
                    @elseif($item->type == 'data')
                        <p>Данные</p>
                    @elseif($item->type == 'tickets')
                        <p>Задачи</p>
                    @endif
                @if($item->is_summary != 1 && isset($item->access->edit) && intval($item->access->edit) === 1)
                    <a class="ajax" href="{{ route('create_record', ['id' => $item->id])}}" class="btn btn-sm btn-default">
                        Добавить
                        @if($item->type == 'data')
                            объект
                        @elseif($item->type == 'tickets')
                            задачу
                        @endif
                    </a>
                    <br>
                @endif
            </div>
        @endforeach
    </div>
</div>