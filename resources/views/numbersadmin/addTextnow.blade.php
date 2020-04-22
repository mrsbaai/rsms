@extends('layouts.numbersadmin')

@section('head')
    <title>Receive-SMS :: Add Textnow Numbers</title>
@stop


@section('content')




    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">

            <h3>Old Login info:</h3><br/>
            <div class="container width-fix col-sm-12">
                <div class="col-sm-6">
                    <input type="text" name="old_user" class="form-control" placeholder="Email" required="required" value="{{$email}}">
                </div>
                <div class="col-sm-6">
                    <input type="text" name="old_pwd" class="form-control" placeholder="Password" required="required" value="{{$password}}">
                </div>
            </div>

            <br/><br/><br/>
            <h3>Update Textnow Number - ({{$count}} in total)</h3><br/>

            {{ Form::open(array('action' => 'adminController@doAddNumber', 'id' => 'add-number-form'))}}
            <div class="container-fluid no-padding ">



                        <div class="form-group">
                        <div class="container width-fix col-sm-12">
                            <div class="col-sm-6">
                                <input type="text" name="user" class="form-control" placeholder="Email" required="required" value="{{$new_email}}">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="number" class="form-control" placeholder="Number?" required="required"><br>
                            </div>
                            <br/>  <br/> 
                        
                            <input type="text" name="pwd"  hidden="hidden" value="{{$password}}">
                            <input type="text" name="id"  hidden="hidden" value="{{$id}}">
                            <input type="text" name="network" value="textnow" required="required" hidden="hidden"><br>
                            <input type="checkbox" name="set_as_checked" id="set_as_checked" value="Set as checked number" checked hidden>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <p class="text-center">
                                            <h1><a style ="color:red;" href="/numbersadmin/addtextnow/delete/{{$id}}">Delete</a></h1>
                                        </p>  
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-center">
                                            <button class="btn btn-lg btn-success" type="submit" id="add">Add</button>
                                        </p> 
                                    </div>
                                </div>
                     
                            </div>


                         
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
