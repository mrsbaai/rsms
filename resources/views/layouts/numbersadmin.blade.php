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

 


                <li><a href="{{ url('/numbersadmin/addtextnow') }}">Add Textnow</a></li>



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
