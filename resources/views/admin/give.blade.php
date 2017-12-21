@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Give Numbers</title>
@stop


@section('content')




    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>Give Numbers</h3>

            {{ Form::open(array('action' => 'adminController@giveNumbers', 'id' => 'add-form'))}}
            <div class="container-fluid no-padding ">
                <div class=" col-lg-3 col-md-3 ">
                </div>

                <div class="col-lg-6 col-md-6  no-padding ">

                    <div class="form-group">
                        <label>User E-mail?</label>
                        <input type="text" name="user_email" class="form-control" placeholder="User E-mail" required="required"><br>
                    </div>

                    <div class="form-group">
                        <label>How many numbers?</label>
                        <input id="amount" name="amount" oninput="getPrice()" class="form-control" type="number" value="1" min="1" max="70" />
                    </div>

                    <div class="form-group">
                        <p class="text-center"><button class="btn btn-lg btn-success" type="submit" id="add">Add</button></p>
                    </div>

                </div>

                <div class=" col-lg-3 col-md-3 ">
                </div>

            </div>
            {{ Form::close() }}


        </div>
    </div>






@stop



@section('bottom')
    <script src="js/bootstrap-number-input.js" ></script>
    <script>
        $('#amount').bootstrapNumber();
        $('#period').bootstrapNumber();
    </script>
@stop
