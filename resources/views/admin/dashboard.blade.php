
@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Administration</title>

@stop

@section('content')

    <div class="container width-fix col-sm-12">

            <h1>Send Money</h1>
            {{ Form::open(array('action' => 'PaymentController@RedirectToPaymentInternal'))}}

            <input type="text" name="amount" id="amount" placeholder="amount">
            <input type="text" name="toemail" id="toemail" placeholder="Receiver email">
            <button id="send" type="submit">send</button>

            {{ Form::close() }}

    </div>



@stop



@section('bottom')

@stop
