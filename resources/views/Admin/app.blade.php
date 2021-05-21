<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel</title>

		<link href="{{ asset('css/app.css') }}" rel="stylesheet">

		<!-- Fonts -->
		<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>


		@stack("css")

	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle=data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">ATS</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/') }}">Home</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						@if (Auth::guest())
							<li>
								<a href="{{ url('admin/login') }}">Login</a>
							</li>

							<li>
								<a href="{{ url('/auth/register') }}">Register</a>
							</li>
						@else
							<li class="dropdown">
								<a href="{{ url('admin/logout') }}" class="dropdown-toggle" data-t aria-expanded="false">{{ Auth::user()->name }}
									<span class="caret"></span>
								</a>
							</li>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}">Logout</a>
								</li>

							</ul>
						@endif
					</ul>
				</div>
			</div>
		</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>


	@stack("javascript")

	</body>
</html>
