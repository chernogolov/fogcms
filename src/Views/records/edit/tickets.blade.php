<div class="card-header">
    @if(isset($data->id))
        Редактирование
    @else
        Новая заявка
    @endif
    @isset($back)
        <a class="ajax float-right" href="{{$back}}"><span class="mdi mdi-remove"></span>&nbsp;Назад</a>
    @endisset
</div>
<div class="card-body">
<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data"
    @if(isset($data->id))
        action="{{ route('regs') . '/edit/' .  $node->id . '/' . $data->id}}"
    @else
        action="{{ route('regs') . '/new/' .  $node->id}}"
    @endif
    >
    {{ csrf_field() }}

    <div class="form-group row">
        @if(isset($data->id))
            <input type="hidden" name="record[id]" value="{{ $data->id }}" list="tickets">
        @endif
    </div>