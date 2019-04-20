<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>
                    <input type="checkbox" class="check_all" title="Выделить все для удаления">
                </th>
                <th>
                   {{__('Value')}}
                </th>
                <th>
                    {{__('Metadata')}}
                </th>
                <th>
                    {{__('Order')}}
                </th>
            </tr>
            @foreach($list as $item)
                <tr>
                    <td>
                        <input form="editForm" type="checkbox" class="for_check" name="clear[{{ $item->id }}]" title="{{__('Delete')}}">
                    </td>
                    <td>
                        <input form="editForm" value="{{ $item->value }}" form="editForm" placeholder="{{__('Enter new value')}}" class="form-control" name="list[{{ $item->id }}][value]">
                    </td>
                    <td>
                        <input form="editForm" value="{{ $item->meta }}" form="editForm" placeholder="{{__('Enter metadata')}}" class="form-control" name="list[{{ $item->id }}][meta]">
                    </td>
                    <td>
                        <input form="editForm" type="number" value="{{ $item->ordering }}" form="editForm" class="form-control" name="list[{{ $item->id }}][ordering]">
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <hr>
    <div class="form-group row">
        <div class="col-sm-6">
            <button type="button" name="addListValue" class="btn btn-default" data-toggle="modal" data-target="#addModal">{{__('Add list')}}</button>
        </div>
        <div class="col-sm-6">
            <div class=" float-right">
                <span class="text-muted">
                    {{__('Go to list')}}&nbsp;&nbsp;<input type="checkbox" name="to_list" value="1" checked>&nbsp;&nbsp;&nbsp;
                </span>
                <button type="submit" form="editForm" class="btn btn-default" name="save_list" value="true">{{__('Save')}}</button>
            </div>
        </div>
    </div>
</form>
<form method="post" class="form-horizontal" id="addValues" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{__('Add list')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea rows="12" form="addValues" name="values" class="form-control" placeholder="{{__('Input data, each with a new line')}}"></textarea>
                </div>
                <div class="modal-footer">
                    <small class="text-muted float-left">{{__('This page be refreshed after update')}}</small>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" name="add_values" class="btn btn-primary" form="addValues" value="add">{{__('Add')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>