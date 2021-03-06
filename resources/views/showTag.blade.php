
@extends('layouts.app')
@include('messages_table_norefresh')


@section('head')

    <meta name="keywords" content="receive, sms, online">
    <meta name="description" content="Received SMS Messages containing '{{$tag}}'">
    <title>SMS Messages Containing [{{$tag}}]</title>

@stop

@section('content')


        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>SMS Messages Containing [{{$tag}}]</h2>
                @yield('messages_table')

            </div>
        </div>

@stop



@section('bottom')

@stop
