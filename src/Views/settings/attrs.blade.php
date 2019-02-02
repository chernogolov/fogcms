<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
        <tr>
            <th>
                <input type="checkbox" class="check_all">
            </th>
            <th>
                id
            </th>
            <th>
                {{__('Type')}}
            </th>
            <th>
                {{__('ALias')}}
            </th>
            <th>
                {{__('Title')}}
            </th>
            <th>
                {{__('Modificator')}}
            </th>
            <th>
                {{__('Metadata')}}
            </th>
        </tr>
        @for ($n = 0;$n< count($attrs); $n++)
            <tr>
                <td>
                    <input type="checkbox" class="for_check" name="delete[{{ $attrs[$n]->id }}]" form="editForm">
                </td>
                <td>
                    {{ $attrs[$n]->id }}
                </td>
                <td>
                    {{ $attrs[$n]->type }}
                </td>
                <td>
                    <a href="{{ route('editattr') . '/' . $attrs[$n]->id }}">{{ $attrs[$n]->name }}</a>
                </td>
                <td>
                    {{ $attrs[$n]->title }}
                </td>
                <td>
                    {{ $attrs[$n]->modificator }}
                </td>
                <td>
                    {{ $attrs[$n]->meta }}
                </td>
            </tr>
        @endfor
        </table>
    </div>
    <button type="submit" class="btn btn-default" onclick="return confirm('{{__('Confirm delete')}}');">{{__('Delete selected')}}</button>
</form>
