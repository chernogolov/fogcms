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
                        {{__('Source')}}
                    </th>
                </tr>
                @foreach ($records as $record)

                <tr>
                    <td>
                        <div class="btn-group dropdown">
                            <button type="button" class="btn btn-light dropdown-toggle btn-sm hover-spin" data-toggle="dropdown" data-spin="cog-{{ $record->id }}"><span class="mdi mdi-settings" id="cog-{{ $record->id }}" >&nbsp;</span><span class="caret"></span></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('view-ticket', ['id' => $record->id]  )}}">Просмотр</a>
                            </div>
                        </div>
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->Number }}
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        @isset($record->description){{ str_limit($record->description, 20) }}@endisset
                    </td>
                    <td class="hidden-xs hidden-sm"  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->created_at }}
                    </td>
                    <td class="hidden-xs"  @if($record->status == 4) style="opacity:0.7" @endif>
                        {{ $record->updated_at }}
                    </td>
                    <td  @if($record->status == 4) style="opacity:0.7" @endif>
                        @isset($record->Source){{ $record->Source }}@endisset
                    </td>
                </tr>
                @endforeach
            </table>
       </div>
    </div>
</div>