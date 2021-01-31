@extends('layouts.app')
@section('head')
    <title>Receive-SMS :: {{$title}}</title>
@stop
@section('content')
<style>
.button.close{
color:#90C6C5;
}
</style>
    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">

            <div class="container">
                <div class="row message">
                    <h2><span class="{{$titleClass}}">{{$title}}</span></h2>
                    <p>{!! $content !!}</p>
                </div>
            </div>
        </div>
    </div>

@stop
