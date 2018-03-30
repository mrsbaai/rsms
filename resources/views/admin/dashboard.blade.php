
@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Administration</title>

@stop

@section('content')

    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Send Money</h1>
            {{ Form::open(array('action' => 'PaymentController@RedirectToPaymentInternal'))}}

            <div class="col-sm-12">
                <div class="col-sm-4">
                    <input  class="form-control" type="text" name="amount" id="amount" placeholder="amount">
                </div>
                <div class="col-sm-4">
                    <input  class="form-control" type="text" name="toemail" id="toemail" placeholder="Receiver email">
                </div>
                <div class="col-sm-4">
                    <button  class="form-control" id="send" type="submit">send</button>
                </div>
                </div>




            {{ Form::close() }}
        </div>
    </div>



@stop



@section('bottom')

@stop
