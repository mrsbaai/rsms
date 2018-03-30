
@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Administration</title>

@stop

@section('content')

    <div class="container width-fix col-sm-12">

            <h1>Send Money</h1>
            <br/><br/>
            {{ Form::open(array('action' => 'PaymentController@RedirectToPaymentInternal'))}}

            <div class="container width-fix col-sm-12">
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


    <div class="spacer"></div>

    <div class="container width-fix col-sm-12">
            <h1>PayPal Accounts</h1>

            <div class="container-fluid no-padding ">
                <div class="col-sm-12 no-padding ">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                @foreach($columns as $column)
                                    <th>{{ $column }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $id => $array)
                                <tr>
                                    @foreach($array as $content)
                                        <td>{{ $content }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

    </div>




@stop



@section('bottom')

@stop
