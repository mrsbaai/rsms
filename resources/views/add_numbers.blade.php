
@extends('layouts.app')


<title>Receive-SMS :: Adding Numbers</title>

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h2>Confirmation</h2>
            <p>You are about to add <b>{{$amount}} @if ($amount !== "1")numbers @else number @endif </b> to your account. {{$price}} will be deducted from your balance.</p>
            {{ Form::open(array('action' => 'userController@addNumbers', 'id' => 'add-form'))}}
            <input name="confirmed-amount" value="{{$amount}}" hidden/>
            <p class="text-center"><button class="btn btn-lg btn-success" type="submit">Confirm</button></p>
            {{Form::close()}}


        </div>


    </div>




@stop



@section('bottom')

@stop
