<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{env('APP_FAVICON', '')}}">

    <!-- Scripts -->
    <script nonce="{{ csp_nonce() }}" src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://cdn.materialdesignicons.com/3.4.93/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" >
    <link href="{{ asset('/vendor/chernogolov/fogcms/public/js/vendor/multiple-select/multiple-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/chernogolov/fogcms/public/css/fogcms.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                            <li class="nav-item @if (Route::currentRouteName() == 'lk') active @endif ">
                                <a class="nav-link"  href="{{ route('lk') }}">Главная</a>
                            </li>
                        @else
                            <li class="nav-item @if (Route::currentRouteName() == 'lk') active @endif ">
                                <a class="nav-link" href="{{ route('lk') }}">Кабинет</a>
                            </li>
                            @if (Gate::allows('view-regs'))
                                <li class="nav-item @if (Route::currentRouteName() == 'regs') active @endif ">
                                    <a class="nav-link" href="{{ route('regs') }}">Журналы</a>
                                </li>
                            @endif
                            @if (Gate::allows('view-admin'))
                                <li class="nav-item @if (Route::currentRouteName() == 'settings') active @endif ">
                                    <a class="nav-link" href="{{ route('settings') }}">Управление</a>
                                </li>
                                <li class="nav-item @if (Route::currentRouteName() == 'users') active @endif ">
                                    <a class="nav-link" href="{{ route('users') }}">Пользователи</a>
                                </li>
                            @endif
                                <li class="nav-item @if (Route::currentRouteName() == 'options' || Route::currentRouteName() == 'account') active @endif ">
                                    <a class="nav-link" href="{{ route('account') }}">Настройки</a>
                                </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
    <script nonce="{{ csp_nonce() }}" src="{{ asset('/vendor/chernogolov/fogcms/public/js/fogcms.js') }}"></script>
    @stack('scripts')

