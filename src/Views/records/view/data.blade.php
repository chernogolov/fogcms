<div class="card-header">
    <div class="row">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-addon">ID</span>
                <input type="text" class="form-control input-sm" placeholder="ID" value="{{ $data->id }}">
            </div>
        </div>
        <div class="col-sm-8">
            @isset($back)
            <a class="ajax btn btn-default float-right btn-sm" href="{{$back}}"><span class="mdi mdi-remove"></span>&nbsp;Назад</a>
            @endisset
        </div>
    </div>
</div>
<div class="card-body" style="padding-bottom: 0">
    <div class="col-sm-6 text-left">
        <small class="text-muted">
            Создано: {{ $data->created_at }}
        </small>
    </div>
    <div class="col-sm-6 text-right">
        <small class="text-muted">
            Обновлено: {{ $data->updated_at }}
        </small>
    </div>
    <div class="clearfix"></div>
    <hr>

