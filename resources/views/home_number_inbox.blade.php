
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
    @if(!Auth::check())
        <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <a href="/register">
                <button class="btn btn-lg btn-danger">
                    Create Your Private Inbox
                </button>
            </a>
        </div>
        <br/><br/><br/>

    @endif
@stop
