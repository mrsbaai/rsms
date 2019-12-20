@extends('layouts.numbersadmin')

@section('head')
    <title>Receive-SMS :: Add Textnow Numbers</title>
@stop


@section('content')




    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>New Textnow Number</h3>

            {{ Form::open(array('action' => 'adminController@doAddNumber', 'id' => 'add-number-form'))}}
            <div class="container-fluid no-padding ">
                <div class=" col-lg-3 col-md-3 ">
                </div>

                <div class="col-lg-6 col-md-6  no-padding ">

                        <div class="form-group">
                        <div class="container width-fix col-sm-12">
                            <div class="col-sm-6">
                                <input type="text" name="user" class="form-control" placeholder="Email" required="required" value="{{$email}}">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="pwd" class="form-control" placeholder="Password" required="required" value="{{$password}}">
                            </div>
                            <br/>  <br/>  <br/>
                            <div class="col-sm-12">
                            <input type="text" name="number" class="form-control" placeholder="Number?" required="required"><br>
                            </div>
                            
  
                            <input type="text" name="network" value="textnow" required="required" hidden="hidden"><br>
                            <input type="checkbox" name="set_as_checked" id="set_as_checked" value="Set as checked number" checked hidden>


                            <div class="form-group">
                                    <span class="text-left"><button class="btn btn-lg btn-danger"  onClick="reload">New</button></span>
                                    <span class="text-right"><button class="btn btn-lg btn-success" type="submit" id="add">Add</button></span>
                                
                            </div>
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
