@extends('layouts.app')
@include('pricing_table')

@section('head')
    <title>Receive-SMS :: Pricing</title>
@stop

@section('content')

@yield('pricing_table')
@stop


@section('bottom')
	<br><br>
    <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <a href="/register">
             <a href="/register" class="btn btn-lg btn-custom">Buy Now And Get Free 10 Days</a>
        </a>
    </div>
    <br><br>
    <div class="container col-sm-12">
        <div class="container col-sm-3 text-center">
        </div>
        <div class="container col-sm-2 text-center">
            <img src="../img/bitcoin.png" title="Bitcoin">
        </div>
        <div class="container col-sm-2 text-center">
            <img src="../img/payza.png" title="Payza">
        </div>
        <div class="container col-sm-2 text-center">
            <img src="../img/paypal.png" title="PayPal">
        </div>
        <div class="container col-sm-3 text-center">
        </div>

    </div>


    <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h2 style="color:white;">
            <a style="color:white; " href="https://sms-verification.net/ref/92a4ze"  title="SMS Verification">Interested In 200+ Numbers? Checkout SMS-Verification.net</a>
        </h2>
    </div>
    <br><br>
@stop