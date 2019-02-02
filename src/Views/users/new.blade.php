<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Fio')}} <strong class="text-danger">*</strong></label>
        <div class="col-sm-8">
            <input type="text" name="user[name]" value="{{ old('user.name') }}" class="form-control{{ $errors->has('user.name') ? ' has-error' : '' }}" id="inputName" form="editForm" autocomplete="off" required="required">
            @if ($errors->has('user.name'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('E-Mail Address') . ' (' . __('Your Login') . ')'}} <strong class="text-danger">*</strong></label>
        <div class="col-sm-8">
            <input type="text" name="user[email]" value="{{ old('user.email') }}" class="form-control{{ $errors->has('user.email') ? ' has-error' : '' }}" form="editForm" autocomplete="off" required="required">
            @if ($errors->has('user.email'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.email') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Phone')}}</label>
        <div class="col-sm-8">
            <input type="text" name="user[phone]" value="{{ old('user.phone') }}" class="form-control{{ $errors->has('user.phone') ? ' has-error' : '' }}" form="editForm" autocomplete="off">
            @if ($errors->has('user.phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.phone') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Desctiption')}}</label>
        <div class="col-sm-8">
            <textarea name="user[description]" class="form-control{{ $errors->has('user.description') ? ' has-error' : '' }}" form="editForm">{{ old('user.description') }}</textarea>
            @if ($errors->has('user.description'))
                <span class="help-block">
                    <strong>{{ $errors->first('user.description') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 col-form-label">{{__('Password')}}</label>
        <div class="col-sm-8">
            <input type="password" name="user[password]" class="form-control{{ $errors->has('user.password') ? ' has-error' : '' }}" form="editForm" autocomplete="off" required="required">
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
            <input type="password" name="user[password_confirmation]" class="form-control" form="editForm" autocomplete="off" required="required">
        </div>
    </div>
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
            </tr>
            @for ($n = 0;$n< count($nodes); $n++)
            <tr>
                <td>
                    @for ($i = 1; $i < $nodes[$n]->depth; $i++)
                    &nbsp;
                    @endfor
                    {{ $nodes[$n]->name }}
                </td>
                <td>
                    <input type="hidden" name="regs[{{ $nodes[$n]->id }}][view]" value="0" id="h_view_{{ $nodes[$n]->id }}">
                    <input type="checkbox" class="checkbox" id="view_{{ $nodes[$n]->id }}">
                </td>
                <td>
                    <input type="hidden" name="regs[{{ $nodes[$n]->id }}][edit]" value="0" id="h_edit_{{ $nodes[$n]->id }}">
                    <input type="checkbox" class="checkbox" id="edit_{{ $nodes[$n]->id }}">
                </td>
                <td>
                    <input type="hidden" name="regs[{{ $nodes[$n]->id }}][delete]" value="0" id="h_delete_{{ $nodes[$n]->id }}">
                    <input type="checkbox" class="checkbox" id="delete_{{ $nodes[$n]->id }}">
                </td>
            </tr>
            @endfor
        </table>
    </div>
    <hr>
    <div class="form-group row">
        <div class="col-sm-12 col-form-label">
            <span class="text-muted">
                {{__('Go to list')}}&nbsp;&nbsp;<input type="checkbox" name="to_list" value="1" checked>&nbsp;&nbsp;&nbsp;
            </span>
            <button type="submit" class="btn btn-default">{{__('Save')}}</button>
        </div>
    </div>
</form>
