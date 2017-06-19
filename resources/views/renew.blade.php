
@extends('layouts.app')

<title>Receive-SMS :: Renew</title>

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h2>Confirmation</h2>
            <p>You are about to add <b>{{$period}} @if ($period !== "1")Months @else Month @endif </b>  to the following @if (count($numbers) === 1)number: @else numbers: @endif</p>
            <ul>
                @foreach ($numbers as $number)
                    <li>{{$number}}</li>
                @endforeach
            </ul>
            <br/>
            <p>{{$price}} Will be deducted from your balance.</p>

            {{ Form::open(array('action' => 'userController@renewNumbers', 'id' => 'confirm-form'))}}
            <input name="confirmed-period" value="{{$period}}" hidden/>
            @foreach ($numbers as $number)
                <input name="confirmed-numbers[]" value="{{$number}}" hidden/>
            @endforeach

            <p class="text-center"><button class="btn btn-lg btn-success" type="submit">Confirm</button></p>
            {{Form::close()}}



        </div>


    </div>




@stop



@section('bottom')

@stop
