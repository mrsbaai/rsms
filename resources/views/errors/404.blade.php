
@extends('layouts.app')

@section('title', 'Receive SMS Online')

<title>Receive-SMS</title>

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <div class="row">
                <h2>You Broke The Balance Of The Internet!!</h2>
                What do you wanna do now? <br/><br/>

                <ul class="text-uppercase">
                    <li><a href="/">Go home</a></li>
                    <li><a href="{{ url('/register') }}">Register a new account</a></li>
                    <li><a href="/pricing">Check out the pricing</a></li>
                    <li><a href="/contact">Contact us</a></li>
                    <li><a href="/faqs">See the frequently asked questions</a></li>
                    <li><a href="{{ url('/api') }}">Learn about our Api service</a></li>
                    <li><a href="{{ url('/login') }}">Login</a></li>
                </ul>



            </div>

        </div>

    </div>



@stop



@section('bottom')


@stop
