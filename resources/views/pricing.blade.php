@extends('layouts.app')
@include('pricing_table')

@section('head')
    <title>Receive-SMS :: Pricing</title>
@stop

@section('content')

@yield('pricing_table')
@stop


@section('bottom')
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
@stop