<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}">
    <link rel="icon" type="image/png" href="{{ URL::asset('img/favicon.png') }}">


    @yield('head')
</head>
<body>

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

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right text-uppercase">

                <li><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>

                <li>
                    <div class="dropdown">
                        <button class="btn btn-clean dropdown-toggle" type="button" data-toggle="dropdown">CHARTS
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/admin/chart/income') }}">Income</a></li>
                            <li><a href="{{ url('/admin/chart/subscribers') }}">Subscribers</a></li>
                            <li><a href="{{ url('/admin/chart/unsubscribers') }}">Unsubscribers</a></li>
                            <li><a href="{{ url('/admin/chart/topups') }}">Top-Ups</a></li>
                            <li><a href="{{ url('/admin/chart/registration') }}">Registrations</a></li>
                            <li><a href="{{ url('/admin/chart/chargebacks') }}">Chargebacks</a></li>
                            <li><a href="{{ url('/admin/chart/coupon') }}">Coupon</a></li>
                        </ul>
                    </div>
                </li>


                <li><a href="{{ url('/admin/contact') }}">Contact</a></li>
                <li><a href="{{ url('/admin/support') }}">Support</a></li>
                <li><a href="{{ url('/admin/mailer') }}">Promo Mail</a></li>
                <li><a href="{{ url('/admin/flatmailer') }}">Flat Mail</a></li>
                <li>
                    <div class="dropdown">
                        <button class="btn btn-clean dropdown-toggle" type="button" data-toggle="dropdown">SHOW
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/admin/topups') }}">Top-Ups</a></li>
                            <li><a href="{{ url('/admin/orders') }}">Orders</a></li>
                            <li><a href="{{ url('/admin/numbers') }}">Numbers</a></li>
                            <li><a href="{{ url('/admin/sources') }}">Sources</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <div class="dropdown">
                        <button class="btn btn-clean dropdown-toggle" type="button" data-toggle="dropdown">EDIT
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/admin/addnumbers') }}">Add Numbers</a></li>
                            <li><a href="{{ url('/admin/give') }}">Give Numbers</a></li>
                            <li><a href="{{ url('/admin/coupon') }}">Make Coupon</a></li>
                        </ul>
                    </div>
                </li>


                    <li>

                        <a  href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form"
                              action="{{ url('/logout') }}"
                              method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>

            </ul>

        </div>


    </div>
</div>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<div class="container width-fix col-sm-12">
            @include('flash::message')
</div>


@yield('content')



<noscript>
    To enjoy full functionality of Receive-SMS it is necessary to enable JavaScript.
    Here are the <a href="http://www.enable-javascript.com/" target="_blank">
        instructions how to enable JavaScript in your web browser</a>.
</noscript>
<script>
    $('#flash-overlay-modal').modal();
</script>
@yield('bottom')

</body>
</html>
