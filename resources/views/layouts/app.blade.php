<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-27352927-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());

        gtag('config', 'UA-27352927-1');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="icon" type="image/png" href="{{ URL::asset('img/favicon.png') }}">

    @yield('head')
</head>
<body>
@include('flash::message')

<div class="navbar navbar-inverse navbar-fixed-top nav-texture" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand logo" href="/"> <span> «</span>Receive-SMS<span>» </span></a>
        </div>
        <div class="navbar-header">
            @yield('share_buttons')
        </div>


        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right text-uppercase">


                @if(Auth::check())
                    <li><a href="/topup" title="Click to top up"><span class="balance"><kbd><i class="fa fa-plus-circle"></i> Balance: ${{Auth::user()->balance}}</kbd></span></a></li>
                    <li><a href="{{ url('/inbox') }}">Inbox</a></li>
                    <li><a href="{{ url('/numbers') }}">Numbers</a></li>
                    <li><a href="{{ url('/account') }}">Account</a></li>
                    <li><a href="{{ url('/api') }}">Api</a></li>
                    <li><a href="{{ url('/support') }}">Support</a></li>


                    <li>

                        <a  href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form"
                              action="{{ url('/logout') }}"
                              method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @else
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/faqs">Faqs</a></li>
                    <li><a href="{{ url('/api') }}">Api</a></li>
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @endif

            </ul>

        </div>


    </div>
</div>






@yield('content')

<div class="spacer"></div>

<noscript>
    To enjoy full functionality of Receive-SMS it is necessary to enable JavaScript.
    Here are the <a href="http://www.enable-javascript.com/" target="_blank">
        instructions how to enable JavaScript in your web browser</a>.
</noscript>


<script>
    $('#flash-overlay-modal').modal();
</script>

@yield('bottom')


<div class="container width-fix col-sm-12 text-center footer-c no-padding no-margin">

    {{Config::get('settings.name')}} &copy; {{ Carbon\Carbon::now()->format('Y') }} All rights reserved.
</div>
</body>
</html>
