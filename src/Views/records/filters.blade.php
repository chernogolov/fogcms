<form method="post" class="form-horizontal ajax-form" id="modalForm" action="{{ route('reg_records', ['id' => $node->id]) }}"  data-destination="records">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-6 col-xs-4">
            <div class="modal fade" id="fieldsSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">{{__('Table view settings')}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach($params['default_fields'] as $dkey => $dfield)
                                    <div class="col-sm-12">
                                        <input type="checkbox" form="modalForm" id="label_{{ $dkey }}" name="fields[{{ $dkey }}]" @if(isset($params['fields'][$dkey])) checked @endif>
                                        <label for="label_{{ $dkey }}">{{ $dfield->title }}</label>
                                    </div>
                                <div class="clearfix"></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" form="modalForm">{{__('Close')}}</button>
                            <button type="submit" data-destination="records" class="btn btn-primary modal_form submit" data-modal="fieldsSettings" data-dismiss="modal" form="modalForm">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <a data-toggle="modal" data-target="#fieldsSettings" class="btn btn-light btn-sm"><span class="mdi mdi-table-row"><span class="hidden-xs">&nbsp;{{__('Table view settings')}}</span></a>
        </div>
        <div class="col-sm-6 col-xs-8 float-right text-right">
            <div class="btn-group clear">
                <button type="submit" class="btn btn-light btn-sm clear_filters submit mdi mdi-close" title="{{__('Clear all')}}" data-destination="records" name="clear_all" form="modalForm" value="true"></button>
                <button type="submit" class="btn btn-light btn-sm clear_filters submit mdi mdi-filter-remove" title="{{__('Clear filters')}}" data-destination="records" name="clear_filters" form="modalForm" value="true"></button>
                <button type="submit" class="btn btn-light btn-sm clear_filters submit mdi mdi-table-row-remove" title="{{__('Clear table row view')}}" data-destination="records" name="clear_fields" form="modalForm" value="true"></button>
                <button type="submit" class="btn btn-light btn-sm clear_filters submit mdi mdi-playlist-remove" title="{{__('Clear order')}}" data-destination="records" name="clear_order" form="modalForm" value="true"></button>
            </div>
        </div>
    </div>
    <br>
</form>
