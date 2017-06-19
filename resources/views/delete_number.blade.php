
@extends('layouts.app')
@include('buy')

@section('head')
    <title>Receive-SMS :: {{$number}}</title>
@stop

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3> <span class="text-danger">Deleting: {{$number}}</span></h3>

            <p>You are about to delete this number: "{{$number}}". Deleting numbers is PERMANENT and NOT refundable. Click "Delete" to confirm.</p>
            {{ Form::open(array('action' => 'userController@doDeleteNumber', 'id' => 'confirm-form'))}}
            <input name="confirmed-delete" value="{{$number}}" hidden/>
            <p class="text-center"><button class="btn btn-lg btn-danger" type="submit">Delete</button></p>
            {{Form::close()}}

        </div>

    </div>



@stop



@section('bottom')

@stop
