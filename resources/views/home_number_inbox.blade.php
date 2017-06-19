
@extends('layouts.app')
@include('messages_table')


@section('head')
    <title>Receive-SMS :: {{$current}}</title>
@stop

@section('content')



        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>[{{$current}}] Inbound Messages - <span class="text-success">Live</span></h2>
                @yield('messages_table')

            </div>
        </div>




@stop



@section('bottom')

@stop
