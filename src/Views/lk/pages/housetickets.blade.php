<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">{{__('Tickets your home')}}</h1>
       <div class="table-responsive">
            <table class="table ">
                <tr>
                    <th>
                        {{__('Theme')}}
                    </th>
                    <th class="hidden-xs hidden-sm">
                        {{__('Created at')}}
                    </th>
                    <th>
                        {{__('Status')}}
                    </th>
                </tr>
                @foreach ($records as $record)
                <tr>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        @isset($record->theme){{ $record->theme }}@endisset
                    </td>
                    <td class="hidden-xs hidden-sm"  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->created_at }}
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        @if($record->status == 1) Новое @elseif ($record->status == 2) В работе @elseif ($record->status == 3) Выполнено @elseif ($record->status == 4) Закрыто автором @endif
                    </td>
                </tr>
                @endforeach
            </table>
       </div>
    </div>
</div>