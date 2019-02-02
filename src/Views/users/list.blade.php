<form method="post" class="form-horizontal" id="usersForm">
    {{ csrf_field() }}
<table class="table table-responsive table-bordered table-striped">
    <tr>
        <th>
            id
        </th>
        <th>
            {{__('fio')}}
        </th>
        <th>
            {{__('Username')}}
        </th>
        <th>
            {{__('Phone')}}
        </th>
        <th>
            {{__('Created at')}}
        </th>
        <th>
            {{__('Description')}}
        </th>
        <th>
            {{__('Delete')}}
        </th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td>
               {{ $user->id }}
            </td>
            <td>
                <a href="{{ route('edit_user',['id' => $user->id]) }}">{{ $user->name }}</a>
            </td>
            <td>
                {{ $user->email }}
            </td>
            <td>
                {{ $user->phone }}
            </td>
            <td>
                {{ $user->created_at }}
            </td>
            <td>
                {{ $user->description }}
            </td>
            <td>
                <input type="checkbox" class="for_check" name="delete[{{ $user->id }}]" form="usersForm">
            </td>
        </tr>
    @endforeach
</table>
    <hr>
    <button type="submit" class="btn btn-default" onclick="return confirm('{{__('Confirm delete')}}?');">{{__('Delete selected')}}</button>
</form>