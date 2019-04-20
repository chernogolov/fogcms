<table class="table items-table">
<tr>
    <th>
        #
    </th>
    <th class="hidden-xs hidden-sm">
        {{__('Created at')}}
    </th>
    <th class="hidden-xs">
        {{__('Updated at')}}
    </th>
    <th class="hidden-xs">
        {{__('Status')}}
    </th>
</tr>
@foreach ($records as $record)
<tr class="@if($record->status == 1) danger @elseif ($record->status == 2) warning @elseif ($record->status == 3) success @endif">
    <td @if($record->status == 4) style="opacity:0.7" @endif>
    <a class="ajax" href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="{{__('Overview')}}">
        {{ $record->id }}
    </a>
    </td >
    <td class="hidden-xs hidden-sm" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="{{__('Overview')}}">
            {{ $record->created_at }}
        </a>
    </td>
    <td class="hidden-xs" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="{{__('Overview')}}">
            {{ $record->updated_at }}
        </a>
    </td>
    <td class="hidden-xs" @if($record->status == 4) style="opacity:0.7" @endif>
        <a class="ajax"  href=" {{ route('view_record', ['id' => $reg_id, 'rid' => $record->id]  )}} " target="_blank"  style="display: block;color: #333;text-decoration: none" title="{{__('Overview')}}">
            @if($record->status == 1) {{__('New')}} @elseif ($record->status == 2) {{__('In work')}} @elseif ($record->status == 3) {{__('Completed')}} @elseif ($record->status == 4) {{__('Close by author')}} @endif
        </a>
    </td>
</tr>
@endforeach
</table>
