<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data"
      xmlns="http://www.w3.org/1999/html">
    {{ csrf_field() }}
    <div class="form-group row">
        @if(isset($user->id))
        <input type="hidden" name="user[id]" value="{{ $user->id }}">
        @endif
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Fio')}} <strong class="text-danger">*</strong></label>
        <div class="col-sm-8">
            <input type="text" name="user[name]" @if(isset($user->name)) value="{{ $user->name }}" @endif class="form-control" id="inputName" form="editForm" autocomplete="off" required="required">
            @if ($errors->has('user.name'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('E-Mail Address') . ' (' . __('Your Login') . ')'}} @if(!isset($user->email)) <strong class="text-danger" >*</strong> @endif</label>
        <div class="col-sm-8">
            <input type="text" disabled="disabled" name="user[email]" @if(isset($user->email)) value="{{ $user->email }}" @endif class="form-control" form="editForm" autocomplete="off" required="required">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPhone" class="col-sm-4 col-form-label">{{__('Phone') . ' (' . __('Only digit') . ')'}}</label>
        <div class="col-sm-8">
            <input type="text" id="inputPhone" name="user[phone]" @if(isset($user->phone)) value="{{ $user->phone }}" @endif class="form-control" form="editForm">
            @if ($errors->has('user.phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.phone') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Description')}}</label>
        <div class="col-sm-8">
            <textarea name="user[description]"  class="form-control" form="editForm">@if(isset($user->description)){{ $user->description }}@endif</textarea>
            @if ($errors->has('user.description'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.description') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <br>
    <div class="col-sm-12 text-right">
        <span class="text-muted">{{__('New password (if need)')}}</span>
    </div>
    <br>
    <br>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Password')}}</label>
        <div class="col-sm-8">
            <input type="password" name="user[password]" class="form-control" form="editForm" autocomplete="off" >
            @if ($errors->has('user.password'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.password') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Confirm Password')}}</label>
        <div class="col-sm-8">
            <input type="password" name="user[password_confirmation]" class="form-control" form="editForm" autocomplete="off" >
        </div>
    </div>
    @if(isset($nodes))
    <hr>
    <h5>
        {{__('Journal access settings')}}
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered">
                <tr>
                    <th>
                        {{__('Journals')}}
                    </th>
                    <th>
                        {{__('View')}}
                    </th>
                    <th>
                        {{__('Edit')}}
                    </th>
                    <th>
                        {{__('Delete')}}
                    </th>
                    <th>
                        {{__('Notificable')}}
                    </th>
                </tr>
                @for ($n = 0;$n< count($nodes); $n++)
                <tr>
                    <td>
                        <input type="checkbox" class="set-access" id="ch_{{ $nodes[$n]->id }}" data-id="{{ $nodes[$n]->id }}">
                        @for ($i = 1; $i < $nodes[$n]->depth; $i++)
                        &nbsp;
                        @endfor
                        <label for="ch_{{ $nodes[$n]->id }}"> {{ $nodes[$n]->name }}</label>
                    </td>
                    <td>
                        @if(isset($access[$nodes[$n]->id]) && $access[$nodes[$n]->id]->view == 1)
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][view]" value="1" id="h_view_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" checked id="view_{{ $nodes[$n]->id }}">
                        @else
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][view]" value="0" id="h_view_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" id="view_{{ $nodes[$n]->id }}">
                        @endif
                    </td>
                    <td>
                        @if(isset($access[$nodes[$n]->id]) && $access[$nodes[$n]->id]->edit == 1)
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][edit]" value="1" id="h_edit_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" checked id="edit_{{ $nodes[$n]->id }}">
                        @else
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][edit]" value="0" id="h_edit_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" id="edit_{{ $nodes[$n]->id }}">
                        @endif
                    </td>
                    <td>
                        @if(isset($access[$nodes[$n]->id]) && $access[$nodes[$n]->id]->delete == 1)
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][delete]" value="1" id="h_delete_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" checked id="delete_{{ $nodes[$n]->id }}">
                        @else
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][delete]" value="0" id="h_delete_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" id="delete_{{ $nodes[$n]->id }}">
                        @endif
                    </td>
                    <td>
                        @if(isset($access[$nodes[$n]->id]) && $access[$nodes[$n]->id]->send == 1)
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][send]" value="1" id="h_send_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" checked id="send_{{ $nodes[$n]->id }}">
                        @else
                            <input type="hidden" name="regs[{{ $nodes[$n]->id }}][send]" value="0" id="h_send_{{ $nodes[$n]->id }}">
                            <input type="checkbox" class="checkbox" id="send_{{ $nodes[$n]->id }}">
                        @endif
                    </td>
                </tr>
                @endfor
            </table>
    </div>
    @endif
    <hr>
    <div class="form-group row">
        <div class="col-sm-12 text-right">
            <button type="submit" class="btn btn-default">{{__('Save')}}</button>
        </div>
    </div>
</form>
