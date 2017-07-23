
@extends('layouts.app')

@section('head')
    <title>Receive-SMS :: Numbers</title>
@stop


@section('content')

    <script>

        if(window.attachEvent) {
            window.attachEvent('onload', getPrice);
        } else {
            if(window.onload) {
                var curronload = window.onload;
                var newonload = function(evt) {
                    curronload(evt);
                    getPrice(evt);
                };
                window.onload = newonload;
            } else {
                window.onload = getPrice;
            }
        }


        function getRenewPrice() {
            if (document.getElementById("period").value === "NaN"){document.getElementById("period").value = "1";}

            var totalNumbers = document.querySelectorAll('input[type="checkbox"]:checked').length;
            if (totalNumbers == 0){

                document.getElementById("renew-period").style.display  = 'none';
            }else{
                if (document.getElementById("period").value >= 1){
                    document.getElementById("renew-period").style.display  = 'block';
                    document.getElementById("renew").disabled = true;
                    document.getElementById("renew").innerHTML = "...";
                    var url = "../price/" + totalNumbers + "/" + document.getElementById("period").value;
                    try{
                        $.get( url , function( data ) {
                            try {data = JSON.parse(data);}catch(err) {}

                                    if (data.isPossible === false){
                                        document.getElementById("renew").disabled = true;
                                    }else{

                                        document.getElementById("renew").disabled = false;
                                    }

                                    document.getElementById("renew").innerHTML = "Renew ($" + data.price + ")";



                                })
                                .fail(function() {

                                    document.getElementById("renew").disabled = true;
                                });
                    }

                    catch(err){
                        document.getElementById("renew").innerHTML = "...";
                        document.getElementById("renew").disabled = true;
                    }

                }
            }

        }

        function getPrice() {

            if (document.getElementById("amount").value === "NaN"){document.getElementById("amount").value = "1";}
            document.getElementById("add").disabled = true;
            document.getElementById("add").innerHTML = "...";


            if (document.getElementById("amount").value >= 1){
                var url = "../price/" + document.getElementById("amount").value + "/1";
                try{
                    $.get( url , function( data ) {

                        try {data = JSON.parse(data);}catch(err) {}


                        if (data.isPossible === false){
                                    document.getElementById("add").disabled = true;
                                }else{

                                    document.getElementById("add").disabled = false;
                                }

                                document.getElementById("add").innerHTML = "Add ($" + data.price + ")";



                            })
                            .fail(function() {

                                document.getElementById("add").disabled = true;
                            });
                }

                catch(err){
                    document.getElementById("add").innerHTML = "...";
                    document.getElementById("add").disabled = true;
                }

            }


        }
    </script>




    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>Add Numbers</h3>

            <p>Numbers will be valid for <b>1 Month</b> (+10 days). You will be able to renew later as desired. </p>
            {{ Form::open(array('action' => 'userController@addNumbers', 'id' => 'add-form'))}}
            <div class="container-fluid no-padding ">
                <div class=" col-lg-3 col-md-3 ">
                </div>

                <div class="col-lg-6 col-md-6  no-padding ">
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

@if(!$noNumbers)
        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h3>Renew Numbers</h3>
                {{ Form::open(array('action' => 'userController@renewNumbers', 'id' => 'renew-form'))}}
                <div class="container-fluid no-padding ">
                    <p>Select at least one number below.</p>
                    <div class=" col-lg-3 col-md-3 ">
                    </div>
                    <div class="col-lg-6 col-md-6  no-padding ">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                <tr>

                                    <th></th>
                                    <th>[Number]</th>
                                    <th>[Country]</th>
                                    <th>[Expiration]</th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($numbers as $number)
                                    <tr>
                                        <td><input type="checkbox" name="numbers_list[]" value="{{$number->number}}" onchange="getRenewPrice()"></td>
                                        <td><b>[<a style="color:white;" href="inbox/{{$number->number}}" title="Click To See Inbound SMS">{{$number->number}}</a>]</b></td>
                                        <td>[{{$number->country}}]</td>
                                        <td>[{{$number->expiration}}]</td>
                                        <td>
                                                <a href="/delete/{{$number->number}}" class="btn btn-xs btn-danger" style="text-shadow: none;">Delete</a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>


                        </div>
                        <br/>
                        <div id="renew-period" hidden>
                            <div class="form-group">
                                <label>How many months?</label>
                                <input id="period" name="period" oninput="getRenewPrice()" class="form-control" type="number" value="1" min="1" max="74" />
                            </div>

                            <div class="form-group">
                                <p class="text-center"><button class="btn btn-lg btn-success" type="submit" id="renew" disabled>Renew</button></p>
                            </div>
                        </div>



                    </div>


                    <div class=" col-lg-3 col-md-3 ">
                    </div>




                </div>
                {{ Form::close() }}
            </div>

        </div>
@endif




@stop



@section('bottom')
    <script src="js/bootstrap-number-input.js" ></script>
    <script>
        $('#amount').bootstrapNumber();
        $('#period').bootstrapNumber();
    </script>
@stop
