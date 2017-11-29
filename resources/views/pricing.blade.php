@extends('layouts.app')
@include('pricing_table')

@section('head')
    <title>Receive-SMS :: Pricing</title>
@stop

@section('content')

@yield('pricing_table')
<div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <h2 style="color:white;">
        Interested In 200+ Numbers? Checkout <a style="color:white; " href="https://sms-verification.net/"  title="SMS Verification">SMS-Verification.net</a>
    </h2>
</div>

@stop
