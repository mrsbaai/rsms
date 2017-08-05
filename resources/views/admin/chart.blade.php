
@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Administration</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: white;
            color: gray;
        }
    </style>
    {!! Charts::assets() !!}

@stop

@section('content')
<center>
    {!! $chart->render() !!}
</center>
@stop



@section('bottom')

@stop
