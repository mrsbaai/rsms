
@extends('layouts.app')

<title>Receive-SMS :: {{$title}}</title>

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3><span class="{{@$account_form_color}}">{{$title}}</span></h3>
            <p>{{$message}}</p>
            <p class="text-center"><a href="../inbox"><button class="btn btn-lg btn-primary">Go To Inbox</button></a></p>

        </div>


    </div>


@stop



@section('bottom')

@stop
