<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!--[if IE 9]>
      <link href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/css/bootstrap-ie9.min.css" rel="stylesheet">
    <![endif]-->
    <!--[if lte IE 8]>
      <link href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/css/bootstrap-ie8.min.css" rel="stylesheet">
      <link href="{{ asset('/vendor/fogcms/css/lk-ie89.css') }}" rel="stylesheet" >
      <script src="https://cdn.jsdelivr.net/g/html5shiv@3.7.3"></script>
    <![endif]-->

    <!--[if IE 9]>
      <script src="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/js/bootstrap-ie9.min.js"></script>
    <![endif]-->
    <!--[if lte IE 8]>
      <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
      <script src="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/js/bootstrap-ie8.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.js"></script>
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@isset($title) {{$title}} @endisset</title>
    <link rel="icon" type="image/x-icon" href="{{env('APP_FAVICON', '')}}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" >
    <link href="{{ asset('/vendor/fogcms/css/vendor/reset.css') }}" rel="stylesheet" >
    <link href="{{ asset('/vendor/fogcms/css/vendor/fonts.css') }}" rel="stylesheet" >
    @stack('styles')
    <link href="{{ asset('/vendor/fogcms/css/lk.css') }}" rel="stylesheet" >


</head>
<body class="lk" >
    @if(isset($current_account['address.photo']))
        <div class="position-fixed w-100 h-100 bg-photo" style="opacity:0.08;background: url('/imagecache/original/{{$current_account['address.photo']}}');background-size: cover;" >
        </div>
    @endif
    <!--[if IE 8]>
    <div class="alert alert-danger text-center mb-0">
        Внимание! Вы используете устареший браузер IE8. Сайт работает с ограниченными возможностями
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    </div>
    <![endif]-->
    <header>
		<div class="wrapper-header">
			<div class="container">
				<div class="row">
					<nav class="navbar navbar-expand-lg">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						    <div class="d-block m-auto">
	                          <span class="icon-bar icon-bar-first"></span>
	                          <span class="icon-bar icon-bar-next"></span>
	                          <span class="icon-bar icon-bar-last"></span>
	                        </div>
						</button>
						<a class="navbar-brand col-6 col-md-3" href="/">
                            <img src="{{env('APP_LOGO', '')}}" alt="logo" class="full-logo">
                            <img src="{{env('APP_LOGO_SHORT', '')}}" alt="logo" class="short-logo">
                        </a>
						<div class="collapse navbar-collapse col-12 col-sm-6 col-md-5" id="navbarSupportedContent">
						   	<p class="address-text my-auto d-none d-lg-inline-block">
                                <a href="{{route('news')}}" class="header-link">
                                    <span class="mdi mdi-newspaper mdi-light mdi-24px float-left"></span><small class="float-left d-none d-lg-block">{{__('News')}}</small>
                                </a>
                                <a href="{{route('messages')}}" class="header-link">
                                    <span class="mdi mdi-email mdi-light mdi-24px float-left">@if(count($user->unreadNotifications)>0)<sub>{{count($user->unreadNotifications)}}</sub>@endif</span>
                                    <small class="float-left d-none d-lg-block">{{__('Messages')}}</small>
                                </a>
                                <a href="{{route('contacts')}}" class="header-link">
                                    <span class="mdi mdi-contacts mdi-light mdi-24px float-left"></span><small class="float-left d-none d-lg-block">{{__('Contacts')}}</small>
                                </a>
						   	</p>
						   	<div class="slidebar d-lg-none">
						   		<div class="row ">
						   		    <div class="col-1"></div>
							   		<div class="col-10 p-0 slidebar-top d-flex flex-column justify-content-between">
                                        <div class="btn-wrp mt-4">
                                            <a href="{{route('new-ticket')}}" class="btn btn-green btn-small w-100">{{__('Ticket')}}<span>&nbsp;&nbsp;+</span></a>
                                        </div>
										<ul class="list-group">
                                              <li class="list-group-item">
                                                <a href="{{route('support')}}" >{{__('Support center')}}</a>
                                              </li>
                                              <li class="list-group-item">
                                                <a href="{{route('utilities')}}">{{__('Utilites')}}</a>
                                              </li>
                                              <li class="list-group-item active">
                                                <a href="{{route('finance')}}">{{__('Finance')}}</a>
                                              </li>
                                              <li class="list-group-item active">
                                                    <a href="{{route('news')}}">{{__('News')}}</a>
                                              </li>
                                              <li class="list-group-item active">
                                                    <a href="{{route('messages')}}">{{__('Messages')}} @if(count($user->unreadNotifications)>0)<span class="badge badge-pill">{{count($user->unreadNotifications)}}</span>@endif</a>
                                              </li>
                                              <li class="list-group-item active">
                                                    <a href="{{route('contacts')}}">{{__('Contacts')}}</a>
                                              </li>
                                            </ul>
                                            <div class="logout mt-5">
                                                <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" class="d-flex justify-content-between"><span>Выход из системы</span><img src="{{ asset('/vendor/fogcms/img/exit_btn_while.png') }}" alt="exit"></a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
									</div>
								</div>
							</div>
						</div>
                        <div class="lk-user justify-content-end ">
                            <div class="login-foto float-left text-right ">
                                <a class="d-flex align-items-center d-lg-none justify-content-end" href="{{route('lk-profile')}}">
                                    <img class="rounded-circle img-fluid w-50" src="@if(isset($user->image) && strlen($user->image)>0)/imagecache/avatar/{{$user->image}}@else /public/img/default-user.jpg @endif" alt="login">
                                </a>
                                <img class="d-none d-lg-inline img-fluid" src="@if(isset($user->image) && strlen($user->image)>0)/imagecache/avatar/{{$user->image}}@else /public/img/default-user.jpg @endif" alt="login">
                            </div>
                            <div class="login-hrefs float-left dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >@isset($user->name){{str_limit($user->name, 20)}}@endisset&nbsp;</button>
                                <div class="dropdown-menu p-4 text-muted w-100">
                                    <a class="dropdown-item" href="{{route('lk-profile')}}">{{__('Profile settings')}}</a>
                                    <a class="dropdown-item" href="{{route('lk-accounts')}}">{{__('My accounts')}}</a>
                                    <a class="dropdown-item" href="{{route('lk-notifications')}}">{{__('Notifications')}}</a>
                                    <a class="dropdown-item" href="{{route('lk-password')}}">{{__('Password')}}</a>
                                    <div class="dropdown-divider"></div>
                                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" class="dropdown-item"><span>{{__('Exit')}}</span></a>
                                </div>
                            </div>
                        </div>
					</nav>
				</div>
			</div>
		</div>
	</header>
	<main>
		<div class="wrapper-personalArea">
		    @yield('content')
		</div>
	</main>
	<footer>
		<div class="wrapper-footer">
			<div class="container">
				<div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-7 col-lg-7 pr-0 d-block d-md-flex text-md-left text-center"><img class="my-auto" src="{{env('APP_FOOTER_LOGO', '')}}" alt="logo">
                        <p class="m-auto">© <?=date('Y')?>&nbsp;{{__('All rights reserved')}}</p><p class="m-auto">{{__('Version')}}&nbsp;:&nbsp;{{env('APP_VERSION', '')}}</p></div>
                    <div class="col-12 col-md-5 col-lg-3 text-md-right text-center"><a href="/privacy_policy" >{{__('Privacy policy')}}</a></div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>

    <link href="{{ asset('/vendor/fogcms/css/vendor/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/fogcms/css/vendor/slick.css') }}" rel="stylesheet" >
    <link href="{{ asset('/vendor/fogcms/css/vendor/slick-theme.css') }}" rel="stylesheet" >
    <link href="https://cdn.materialdesignicons.com/3.4.93/css/materialdesignicons.min.css" rel="stylesheet">

    <script src="{{ asset('/vendor/fogcms/js/vendor/slick.js') }}"></script>
    <script src="{{ asset('/vendor/fogcms/js/lk.js') }}"></script>
    <script src="{{ asset('/vendor/fogcms/js/vendor/jquery.magnific-popup.min.js') }}"></script>
    @stack('scripts')