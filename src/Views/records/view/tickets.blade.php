<div class="card-header">
    <div class="row">
        <div class="col-sm-4 col-xs-6">
            <div class="input-group">
                <span class="input-group-addon">#</span>
                <input type="text" class="form-control input-sm" placeholder="ID" value="{{ $data->id }}">
            </div>
        </div>
        <div class="col-sm-8 col-xs-6">
            @isset($back)
                <a class="ajax btn btn-default float-right btn-sm" href="{{$back}}"><span class="mdi mdi-remove"></span>&nbsp;Назад</a>
            @endisset
        </div>
    </div>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-sm-6 col-xs-12 text-left">
            <small class="text-muted">
                Создано: {{ $data->created_at }} <br> Обновлено: {{ $data->updated_at }}
            </small>
        </div>
        <div class="col-sm-6  col-xs-12 right left-xs">
        <br class="visible-xs">
        <span id="status_{{$data->id}}">
            @if($data->status == 1) Новая @elseif ($data->status == 2) В работе @elseif ($data->status == 3) Выполнено @elseif ($data->status == 4) ЗАКРЫТО АВТОРОМ @endif
        </span>
            <br>
            <div class="btn-group" style="margin-top: 5px;" >
                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">Изменить статус&nbsp;<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a data-sid="1" data-id="{{$data->id}}" class="change_status" @if($data->status != 1) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>Новая</a></li>
                    <li><a data-sid="2" data-id="{{$data->id}}" class="change_status" @if($data->status != 2) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>В работу</a></li>
                    <li><a data-sid="3" data-id="{{$data->id}}" class="change_status" @if($data->status != 3) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>Выполнено</a></li>
                </ul>
            </div>
        </div>
    </div>
    <hr>

