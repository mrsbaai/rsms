
@extends('layouts.admin')




@section('head')



       <title>Receive-SMS :: Administration</title>

       <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('content')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#datepicker" ).datepicker();
        } );
    </script>

    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Active Coupons</h1>

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
    </div>
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Add Coupon</h1>

            {{ Form::open(array('action' => 'adminController@addCoupon', 'id' => 'mailer-form'))}}

            <input type="text" name="code" class="form-control" placeholder="code" required="required"><br>
            <input type="text" name="minimum_price" class="form-control" placeholder="Minimum price" required="required"><br>
            <input type="text" id="datepicker" name="expiration" class="form-control" placeholder="Expiration" required="required"><br>
            <input type="text" name="paymentsystem_id" class="form-control" placeholder="Payment System" required="required"><br>
            <input type="text" name="value" class="form-control" placeholder="value (ex 50%)" required="required"><br>

            <br/>
            <div class="text-right">
                <input type="submit" class="btn btn-lg btn-success btn-send" value="ADD">
            </div>
            {{ Form::close() }}

        </div>
    </div>

@stop



@section('bottom')
@stop
