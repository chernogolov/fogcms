<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Journal name')}}</label>
        <div class="col-sm-8">
            <input type="text" name="reg[name]" class="form-control" id="inputName" placeholder="{{__('Name')}}" value="@isset($node->name){{ $node->name}}@endisset" form="editForm" autocomplete="off">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputParent" class="col-sm-4 text-left">{{__('Parent journal')}}</label>
        <div class="col-sm-8">
            <select name="reg[parent]" class="form-control" form="editForm" @if(isset($node->name)) disabled title="{{__('Change parent or order in list')}}" @endif>
                @foreach ($nodes as $item)
                    <option value="{{ $item->id }}" @if(isset($node->parent_id)) @if($node->parent_id == $item->id) selected @endif @endif>
                        @for ($i = 0; $i < $item->depth; $i++)&nbsp;@endfor{{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputParent" class="col-sm-4 text-left">{{__('View total')}}</label>
        <div class="col-sm-8">
            <select name="reg[is_summary]" class="form-control" form="editForm">
                <option value="0" @if(isset($node) && $node->is_summary === 0) selected @endif>{{__('No')}}</option>
                <option value="1" @if(isset($node) && $node->is_summary === 1) selected @endif>{{__('Yes')}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputParent" class="col-sm-4 text-left">{{__('Available for registred users')}}</label>
        <div class="col-sm-8">
            <select name="reg[is_public]" class="form-control" form="editForm">
                <option value="0" @if(isset($node) && $node->is_public === 0) selected @endif>{{__('No')}}</option>
                <option value="1" @if(isset($node) && $node->is_public === 1) selected @endif>{{__('Yes')}}</option>
            </select>
        </div>
    </div>
    @if(isset($node) && $node->is_summary === 0)
        <div class="form-group row">
            <label for="inputParent" class="col-sm-4 text-left">{{__('Journal type')}}</label>
            <div class="col-sm-8">
                <select name="reg[type]" class="form-control" form="editForm">
                    <option value="data" @if(isset($node) && $node->type === "data") selected @endif>{{__('Data')}}</option>
                    <option value="tickets" @if(isset($node) && $node->type === "tickets") selected @endif>{{__('Tickets')}}</option>
                </select>
            </div>
        </div>
    @endif
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Icon')}} (<small class="text-muted">jpg, png, gif</small>)
        </label>
            @if(isset($node) && isset($node->image) && $node->image != '')
                <div class="col-sm-2">
                    <img src="/imagecache/original/{{ $node->image }}" class="img-responsive">
                </div>
                <div class="col-sm-6">
            @else
                <div class="col-sm-8">
            @endif
            <input type="file" name="reg[image]" class="form-control" id="inputName" value="@isset($node->name) {{ $node->name }} @endisset" form="editForm"
             accept=".jpg,.jpeg,.png,.gif, image/jpeg,image/png,image/gif">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Print template')}} (<small class="text-muted">doc, docx</small>)<br>
            <small class="text-muted">{{__('Set variables as ')}} ${...}</small>
        </label>
        @if(isset($node) && isset($node->print_template) && $node->print_template != '')
        <div class="col-sm-2">
            <a href="{{ $node->print_template }}" target="_blank">
                {{__('Download')}}
            </a>
        </div>
        <div class="col-sm-6">
            @else
            <div class="col-sm-8">
                @endif
                <input type="file" name="reg[print_template]" class="form-control" id="inputName" form="editForm">
            </div>
        </div>

    @if(isset($node) && $node->is_summary === 0)
    <hr>
    <div class="row">
        <div class="col-sm-3 text-left">
            <span class="text-muted">
                {{__('Journal attributes')}}
            </span>
        </div>
        <div class="col-sm-6 text-left">
            <div class="row">
                <div class="col-sm-8">
                    <select name="copy[from]" class="form-control" form="editForm">
                        <option selected="selected"></option>
                        @foreach ($nodes as $item)
                            <option value="{{ $item->id }}">
                                @for ($i = 0; $i < $item->depth; $i++)&nbsp;@endfor{{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <button type="submit" name="copy_attrs" value="true" class="btn btn-default">{{__('Copy')}}</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#attrModal">
                <i class="fas fa-plus-circle"></i>&nbsp;{{__('Add attributes')}}
            </button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>
                        <span class="mdi mdi-delete mdi-18px"></span>
                    </th>
                    <th>
                        {{__('Type')}}
                    </th>
                    <th>
                        {{__('Alias')}}
                    </th>
                    <th>
                        {{__('Title')}}
                    </th>
                    <th>
                        <span class="mdi mdi-pin mdi-18px" title="{{__('View default')}}"></span>
                    </th>
                    <th>
                        <span class="mdi mdi-account-multiple mdi-18px" title="{{__('For all')}}"></span>
                    </th>
                    <th>
                        <span class="mdi mdi-star mdi-18px" title="{{__('Required')}}"></span>
                    </th>
                    <th>
                        <span class="mdi mdi-sort-numeric mdi-18px" title="{{__('Order')}}"></span>
                    </th>
                    <th>

                    </th>
                </tr>
                @if(isset($default_fields))
                    @foreach($default_fields as $fname => $fdata)
                    <tr>
                        <td>
                        </td>
                        <td>
                            default
                        </td>
                        <td>
                            {{$fname}}
                        </td>
                        <td>
                            {{ $fdata['title'] }}
                        </td>
                        <td>
                            <input type="checkbox"
                            @if(in_array($fname, explode(',', $node->default_fields)))
                               checked
                            @endif
                            name="default_fields[view][{{$fname}}]">
                        </td>
                        <td>
                            <input type="checkbox" name="default_fields[view][{{$fname}}]" id="">
                        </td>
                        <td>
                            <input type="checkbox" id="checked" checked disabled >
                        </td>
                        <td>
                            {{--<input type="number" value="{{ $regs_attrs[$i]->ordering }}" name="attrs[{{ $regs_attrs[$i]->id }}][ordering]" class="form-control" style="width: 70px;">--}}
                        </td>
                        <td>
                        </td>
                    </tr>
                    @endforeach
                @endif
                @for ($i = 0;$i< count($regs_attrs); $i++)
                <tr>
                    <td>
                        <input type="checkbox" name="delete[{{ $regs_attrs[$i]->id }}]">
                    </td>
                    <td>
                        {{ $regs_attrs[$i]->type }}
                    </td>
                    <td>
                        {{ $regs_attrs[$i]->name }}
                    </td>
                    <td>
                        {{ $regs_attrs[$i]->title }}
                    </td>
                    <td>
                        @if($regs_attrs[$i]->is_filter == 0)
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_filter]" value="0" id="h_is_filter_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox" class="checkbox" id="is_filter_{{ $regs_attrs[$i]->id }}">
                        @else
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_filter]" value="1" id="h_is_filter_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox" checked class="checkbox" id="is_filter_{{ $regs_attrs[$i]->id }}" >
                        @endif
                    </td>
                    <td>
                        @if($regs_attrs[$i]->is_public == 0)
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_public]" value="0" id="h_is_public_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox" class="checkbox" id="is_public_{{ $regs_attrs[$i]->id }}" >
                        @else
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_public]" value="1" id="h_is_public_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox"  checked class="checkbox" id="is_public_{{ $regs_attrs[$i]->id }}" >
                        @endif
                    </td>
                    <td>
                        @if($regs_attrs[$i]->is_required == 0)
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_required]" value="0" id="h_is_required_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox" class="checkbox" value="0" id="is_required_{{ $regs_attrs[$i]->id }}" >
                        @else
                            <input type="hidden" name="attrs[{{ $regs_attrs[$i]->id }}][is_required]" value="1" id="h_is_required_{{ $regs_attrs[$i]->id }}">
                            <input type="checkbox" checked class="checkbox" id="is_required_{{ $regs_attrs[$i]->id }}" >
                        @endif
                    </td>
                    <td>
                        <input type="number" value="{{ $regs_attrs[$i]->ordering }}" name="attrs[{{ $regs_attrs[$i]->id }}][ordering]" class="form-control" style="width: 70px;">
                    </td>
                    <td>
                        <a class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_<?=$regs_attrs[$i]->name?>">
                            <span class="mdi mdi-th-list"></span>
                        </a>
                        <div class="modal fade" id="modal_<?=$regs_attrs[$i]->name?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">{{__('Advanced settings')}} {{ $regs_attrs[$i]->title }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-responsive table-bordered">
                                            <tr>
                                                <th class="col-sm-4">
                                                    {{__('Param')}}
                                                </th>
                                                <th class="col-sm-8">
                                                    {{__('Value')}}
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{__('Modificator')}}
                                                </td>
                                                <td>
                                                    {{ $regs_attrs[$i]->modificator }}
                                                </td>
                                            </tr>
                                            @if($regs_attrs[$i]->type == 'chars')
                                                <tr>
                                                    <td>
                                                        {{__('Hint')}}<br><small class="text-muted">{{__('Input data, each with a new line')}}</small>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" rows="4" name="attrs[{{ $regs_attrs[$i]->id }}][datalist]">{{ $regs_attrs[$i]->datalist }}</textarea>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($regs_attrs[$i]->type == 'text')
                                            <tr>
                                                <td>
                                                    {{__('Prefix')}}<br>
                                                </td>
                                                <td>
                                                    <textarea class="form-control" rows="4" name="attrs[{{ $regs_attrs[$i]->id }}][datalist]">{{ $regs_attrs[$i]->datalist }}</textarea>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($regs_attrs[$i]->type == 'link')
                                            <tr>
                                                <td>
                                                    {{__('Finds similar objects')}}</small>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="attrs[{{ $regs_attrs[$i]->id }}][datalist]">
                                                        <option value="0" @if(isset($regs_attrs[$i]->datalist) && $regs_attrs[$i]->datalist == 0) selected @endif>{{__('No')}}</option>
                                                        <option value="1" @if(isset($regs_attrs[$i]->datalist) && $regs_attrs[$i]->datalist == 1) selected @endif>{{__('Yes')}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>

                </tr>
                @endfor
            </table>
        </div>
    </div>
    <div class="modal fade" id="attrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Check attribute')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body  table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>
                                    <input type="checkbox" class="check_all">
                                </th>
                                <th>
                                    {{__('Type')}}
                                </th>
                                <th>
                                    {{__('Alias')}}
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
                                    <input type="checkbox" class="for_check" name="attr[{{ $attrs[$n]->id }}]" form="editForm">
                                </td>
                                <td>
                                    {{ $attrs[$n]->type }}
                                </td>
                                <td>
                                    {{ $attrs[$n]->name }}
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
                    <div class="modal-footer">
                        <small class="text-muted float-left">{{__('This page be refreshed after update')}}</small>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    <br>
    @endif
    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-right">
            <span class="text-muted">
                {{__('Go to list')}}&nbsp;&nbsp;<input type="checkbox" name="to_list" value="1" checked>&nbsp;&nbsp;&nbsp;
            </span>
            <button type="submit" class="btn btn-default">{{__('Save')}}</button>
        </div>
    </div>
</form>