<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">{{__('My tickets')}}</h1>
       <div class="table-responsive">
            <table class="table ">
                <tr>
                    <th class="pl-4">
                        <span class="mdi mdi-cogs mdi-18"></span>
                    </th>
                    <th>
                        №
                    </th>
                    <th>
                        {{__('Theme')}}
                    </th>
                    <th class="hidden-xs hidden-sm">
                        {{__('Created at')}}
                    </th>
                    <th class="hidden-xs">
                        {{__('Updated at')}}
                    </th>
                    <th>
                        {{__('Status')}}
                    </th>
                </tr>
                @foreach ($records as $record)
                <tr>
                    <td>
                        <div class="btn-group dropdown">
                            <button type="button" class="btn btn-light dropdown-toggle btn-sm hover-spin" data-toggle="dropdown" data-spin="cog-{{ $record->id }}"><span class="mdi mdi-settings" id="cog-{{ $record->id }}" >&nbsp;</span><span class="caret"></span></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('view-ticket', ['id' => $record->id]  )}}">Просмотр</a>
                                @if($record->status != 4)
                                    <a class="dropdown-item" href="{{ route('close-ticket', ['id' => $record->id]) }}" onclick="return confirm('Действительно закрыть?');">Закрыть</a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->id }}
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        @isset($record->entry){{ $record->entry }}@endisset
                    </td>
                    <td class="hidden-xs hidden-sm"  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->created_at }}
                    </td>
                    <td class="hidden-xs"  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->updated_at }}
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