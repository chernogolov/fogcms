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
      <script src="https://cdn.jsdelivr.net/g/html5shiv@3.7.3"></script>
    <![endif]-->

    <!--[if gte IE 9]><!-->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!--<![endif]-->
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

	<link href="{{ asset('css/app.css') }}" rel="stylesheet" >

    <link href="https://cdn.materialdesignicons.com/3.4.93/css/materialdesignicons.min.css" rel="stylesheet">

    <link href="{{ asset('/vendor/fogcms/css/vendor/reset.css') }}" rel="stylesheet" >
    <link href="{{ asset('/vendor/fogcms/css/vendor/fonts.css') }}" rel="stylesheet" >

	<link href="{{ asset('/vendor/fogcms/css/lk.css') }}" rel="stylesheet" >

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
</head>
<body class="home">
	<header>
		<div class="wrapper-header">
			<div class="container">
				<div class="row">
					<nav class="navbar navbar-expand-lg">
					  	<a class="navbar-brand col-5 col-md-3 " href="/"><img class="float-left" src="{{env('APP_LOGO', '')}}" alt="logo"></a>
					    <div class="col text-right">
							<div class="login-hrefs">
								<a href="/">{{__('Login')}}</a> <span>&nbsp;|&nbsp;</span> <a href="/register">{{__('Register')}}</a>
							</div>
						</div>
					  </div>
					</nav>
				</div>
			</div>
		</div>
	</header>
	<main >
        @yield('content')
    </main>
	<footer>
		<div class="wrapper-footer">
			<div class="container">
				<div class="row justify-content-between align-items-center">
					<div class="col-12 col-md-7 col-lg-5 pr-0 d-block d-md-flex text-md-left text-center"><img class="my-auto" src="{{env('APP_FOOTER_LOGO', '')}}" alt="logo">
					<p class="m-auto">© <?=date('Y')?>&nbsp;{{__('All rights reserved')}}</p></div>
					<div class="col-12 col-md-5 col-lg-3 text-md-right text-center"><a href="#" >{{__('Privacy policy')}}</a></div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>

<link href="{{ asset('/vendor/fogcms/css/vendor/magnific-popup.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/fogcms/css/vendor/slick.css') }}" rel="stylesheet" >
<link href="{{ asset('/vendor/fogcms/css/vendor/slick-theme.css') }}" rel="stylesheet" >


<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/vendor/fogcms/js/vendor/slick.js') }}"></script>
<script src="{{ asset('/vendor/fogcms/js/lk.js') }}"></script>
<script src="{{ asset('/vendor/fogcms/js/vendor/jquery.magnific-popup.min.js') }}"></script>
@stack('scripts')