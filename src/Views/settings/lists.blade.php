<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="table-responsive">
        <table class="table table-striped table-bordered ">
        <tr>
            <th>
                <input type="checkbox" class="check_all">
            </th>
            <th>
                {{__('Attribute')}}
            </th>
            <th>
                {{__('Count values')}}
            </th>
        </tr>
        @for ($n = 0;$n< count($lists); $n++)
            <tr>
                <td>
                    <input type="checkbox" class="for_check" name="clear[{{ $lists[$n]->id }}]" form="editForm">
                </td>
                <td>
                    <a href=" {{ route('editlist', ['id' => $lists[$n]->id]) }} ">{{ $lists[$n]->title }}</a>
                </td>
                <td>
                    {{ count($lists[$n]->data) }}
                </td>
            </tr>
        @endfor
    </table>
    </div>
    <button type="submit" class="btn btn-default" onclick="return confirm('{{__('Really clear')}}?');" name="clear_list">{{__('Clear selected')}}</button>
</form>
