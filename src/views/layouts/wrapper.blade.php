<!DOCTYPE html>
<html lang="en">
<head>
	<base href="{{ URL::to('/') }}/" />
	<title>{{ $page_title or "Here must be page_title" }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/demos/admin/css/alertify.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/demos/admin/css/sweetalert2.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/demos/admin/css/style.css') }}" />
	<link rel="stylesheet" href="{{ asset('public/demos/admin/css/bootstrap-select.min.css') }}">

	<link rel="icon" href="favicon.ico" />
	<link rel="shortcut icon" href="favicon.ico" />
	@yield('links')

</head>
<body>
	<div id="preloader">
		<div class="preload"></div>
	</div>
	<style media="screen">
		#preloader{
			position: fixed;
			top: 0;
			bottom: 0;
			right: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 999;
			background-color: rgba(255,255,255,1);
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.preload {
			width: 100px;
			height: 100px;
			border-radius: 100%;
			border-top: 5px solid;
			border-color: #0ab1cf;
			animation: animat .2s linear infinite;
		}
		@keyframes animat {
			100% {
				transform: rotate(360deg);
			}
		}
	</style>

	<a id='up_button' class="up_button" href="#">
		<i class="glyphicon glyphicon-menu-up"></i>
	</a>
	<div class='admin-container'>
		@if (Auth::guard('account')->check())
			@include('admin::layouts.header')
			@include('admin::layouts.leftbar')
			<div class="hamburger">
				<span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
			</div>
		@endif
		@yield('page')
	</div>

	<script type="text/javascript" src="{{ asset('public/demos/admin/js/jquery-2.2.4.min.js') }}"></script>
	<script type="text/javascript">
		$(window).on('load',function(){
			var preloader = $('#preloader');
			preloader.delay(200).fadeOut('slow');
		});
	</script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/moment.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('public/demos/admin/js/alertify.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/sweetalert2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/jquery.botnd-upload.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/admin.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/localization/messages_ru.js') }}"></script>

	<script type="text/javascript">
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
	</script>
	@yield('scripts')
</body>

</html>
