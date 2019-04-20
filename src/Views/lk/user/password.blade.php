<div class="col-12">
    <div class="container-input">
       <h1 class="blue">{{__('Edit password')}}</h1>
       <form method="post" class="form-horizontal mt-5" id="editForm" enctype="multipart/form-data"
          xmlns="http://www.w3.org/1999/html">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">{{__('New password')}}</label>
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
        <hr>
        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-green float-right">{{__('Save')}}</button>
            </div>
        </div>
    </form>
    </div>
</div>

