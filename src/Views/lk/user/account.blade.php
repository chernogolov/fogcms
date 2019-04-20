<div class="col-12">
    <div class="container-input">
       <h4 class="blue">{{__('Edit your account')}}</h4>
       <form method="post" class="form-horizontal mt-5" id="editForm" enctype="multipart/form-data"
          xmlns="http://www.w3.org/1999/html">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="inputPhoto" class="col-sm-4 col-form-label">{{__('Your photo')}}</label>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-6 ">
                        <img class="img-fluid" src="@if(isset($user->image))/imagecache/original/{{$user->image}}@else /public/img/default-user.jpg @endif" alt="login">
                    </div>
                </div>
                <input type="file" accept="image/jpeg,image/png" name="user[image]" class="form-control mt-4" form="editForm" placeholder="{{__('Change you photo')}}">
                @if ($errors->has('user.image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.image') }}</strong>
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
            <label for="inputPhone" class="col-sm-4 col-form-label">{{__('Phone')}}</label>
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
            <label for="inputName" class="col-sm-4 col-form-label">{{__('About Me')}}</label>
            <div class="col-sm-8">
                <textarea name="user[description]"  class="form-control about-me" form="editForm">@if(isset($user->description)){{ $user->description }}@endif</textarea>
                @if ($errors->has('user.description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.description') }}</strong>
                    </span>
                @endif
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

