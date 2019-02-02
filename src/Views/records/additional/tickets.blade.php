<table class="table table-striped table-responsive items-table">
<tr>
    <th>
        №
    </th>
    <th class="hidden-xs hidden-sm">
        Добавлено
    </th>
    <th class="hidden-xs">
        Обновлено
    </th>
    <th class="hidden-xs">
        Статус
    </th>
</tr>
@foreach ($records as $record)
<tr class="@if($record->status == 1) danger @elseif ($record->status == 2) warning @elseif ($record->status == 3) success @endif">
    <td @if($record->status == 4) style="opacity:0.7" @endif>
    <a class="ajax" href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="Просмотр">
        {{ $record->id }}
    </a>
    </td >
    <td class="hidden-xs hidden-sm" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="Просмотр">
            {{ $record->created_at }}
        </a>
    </td>
    <td class="hidden-xs" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="Просмотр">
            {{ $record->updated_at }}
        </a>
    </td>
    <td class="hidden-xs" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="Просмотр">
            @if($record->status == 1) Новая @elseif ($record->status == 2) В работе @elseif ($record->status == 3) Выполнено @elseif ($record->status == 4) Закрыто автором @endif
        </a>
    </td>
</tr>
@endforeach
</table>
