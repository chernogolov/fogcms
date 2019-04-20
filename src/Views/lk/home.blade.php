@extends('fogcms::layouts.home')

@section('content')
    <div class="wrapper-preim">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 ">
                    <div class="typo pr-md-2 pr-lg-5">
                        <h1 class="title">Личный кабинет &nbsp &#8211</h1>
                        <p class="title-p">это удобный механизм для взаимодействия
                            управляющей компании и жильцов многоквартирных домов</p>
                        <ul class="d-none d-md-block">
                            <li>Передавайте показания ПУ</li>
                            <li>Формируйте квитанции</li>
                            <li>Создавайте обращения</li>
                            <li>Получайте оперативную информацию</li>
                            <li>Просматривайте новости и объявления</li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="container-input">
                        <h1 class="blue">{{__('Log in to your account')}}</h1>
                        <form class="mt-4" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="col-form-label ">{{ __('E-Mail Address') }}</label>
                                <input name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  type="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $errors->first('email') }}</strong>
                                   </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label ">{{ __('Password') }}</label>
                                <input name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"  type="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $errors->first('password') }}</strong>
                                   </span>
                                @endif
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-green w-100">{{__('Log-in')}}</button>
                            </div>
                            <div class="form-group">
                                {{__('Or using social account')}}
                                <br><br>
                                <?=Form::uLogin()?>
                            </div>
                        </form>
                        <div class="data_processing d-flex">
                            <p class="data_processing-text">
                                Отправляя форму, Вы подтверждаете согласие с обработкой персональных данных.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
