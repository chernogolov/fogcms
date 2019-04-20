<hr>
<h5 class="text-right text-muted mb-3">
    Контактные данные
</h5>
<div class="row">
    <div class="col-lg-4">
        <label class="text-muted">
            ФИО
            <strong class="text-danger">*</strong>
        </label>
    </div>
    <div class="col-lg-8">
        <input type="text" name="user[name]" class="form-control form-editable user-form" @if(isset($user['name']) && !isset($user['tmp'])) value="{{$user['name']}}" disabled @endif required="required" @isset($user['name'])data-value="{{$user['name']}}" @endisset>
        @if ($errors->has('user.name'))
            <span class="text-danger">
                <strong>{{ $errors->first('user.name') }}</strong>
            </span>
        @endif
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-4">
        <label class="text-muted">
            Электронный адрес
            <strong class="text-danger">*</strong>
        </label>
    </div>
    <div class="col-lg-8">
        <input type="text" name="user[email]" class="form-control form-editable user-form" @if(isset($user['name']) && !isset($user['tmp'])) value="{{$user['email']}}" disabled @endif @isset($user['email']) data-value="{{$user['email']}}" @endisset>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-4">
        <label class="text-muted">
            Телефон
        </label>
    </div>
    <div class="col-lg-8">
        <input type="tel" name="user[phone]" class="form-control form-editable user-form" @if(isset($user['name']) && !isset($user['tmp'])) value="{{$user['phone']}}" disabled @endif required="required" @isset($user['phone']) data-value="{{$user['phone']}}" @endisset>
        @if ($errors->has('user.phone'))
            <span class="text-danger">
                <strong>{{ $errors->first('user.phone') }}</strong>
            </span>
        @endif
    </div>
</div>
@isset($user)
<br>
<div class="row">
    <div class="col-lg-12 text-right">
        <small class="text-muted">
            Вы можете изменить контактные данные в <a style="color:#4aa0e6;text-decoration: underline" href="{{route('lk-profile')}}">настройках</a>
        </small>
    </div>
</div>
@else
@endisset
<hr>