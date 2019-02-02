<table class="table table-striped table-responsive">
    <tr>
        <th>
            №
        </th>
        <th>
            Наименование
        </th>
        <th>
            Создание
        </th>
        <th>
            Обновление
        </th>
        <th></th>
    </tr>
@foreach ($records as $record)
    <tr>
        <td>
            {{ $record->id }}
        </td>
        <td>
            {{ $record->title }}
        </td>
        <td>
            {{ $record->created_at }}
        </td>
        <td>
            {{ $record->updated_at }}
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown"><span class="mdi mdi-cog">&nbsp;</span><span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Новая</a></li>
                    <li><a href="#">В работу</a></li>
                    <li><a href="#">Выполнено</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
</table>