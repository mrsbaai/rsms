@extends('layouts.admin')

@section('head')
    <title>Receive-SMS :: Add Numbers</title>
@stop


@section('content')




    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>Add Numbers</h3>

            {{ Form::open(array('action' => 'adminController@doAddNumber', 'id' => 'add-number-form'))}}
            <div class="container-fluid no-padding ">
                <div class=" col-lg-3 col-md-3 ">
                </div>

                <div class="col-lg-6 col-md-6  no-padding ">

                        <div class="form-group">
                        <div class="container width-fix col-sm-12">
                            <div class="col-sm-6">
                                <input type="text" name="user" class="form-control" placeholder="Email" required="required" value="email">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="pwd" class="form-control" placeholder="Password" required="required" value="password">
                            </div>
                            
                        </div>

                    <div class="form-group">
                        <input type="text" name="number" class="form-control" placeholder="Number?" required="required"><br>
                    </div>

  
                            <input type="text" name="network" value="textnow" class="form-control" placeholder="Network" required="required" hidden><br>
                            <input class="form-check-input" type="checkbox" name="set_as_checked" id="set_as_checked" value="Set as checked number" checked hidden>

    

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
