
@extends('layouts.app')
@include('buy')

@section('head')
    <title>Receive-SMS :: Top-Up</title>
@stop

@section('content')
    <script>

        if(window.attachEvent) {
            window.attachEvent('onload', formLoad);
        } else {
            if(window.onload) {
                var curronload = window.onload;
                var newonload = function(evt) {
                    curronload(evt);
                    formLoad(evt);
                };
                window.onload = newonload;
            } else {
                window.onload = formLoad;
            }
        }

        function formLoad() {
            document.getElementById("amount").value = "0";
            document.getElementById("type").value = "0";
            document.getElementById("coupon").value = "";
            document.getElementById("price").innerHTML = "$0";
        }

        function formChange() {
            document.getElementById("coupon").value = "";
            document.getElementById("top").innerHTML = "$" + document.getElementById("amount").value;
			document.getElementById("price").innerHTML = "$" + document.getElementById("amount").value;

            if (document.getElementById("amount").value !== "0"  && document.getElementById("type").value !== "0"){
                document.getElementById("pay_button").disabled = false;
            }

        }


        function applyCoupon(val) {

            if (document.getElementById("coupon").value == ""){
                alert("Please enter a coupon code.");
                return;
            }
            if(document.getElementById("amount").value == "0"){
                alert("Please choose an amount to add to your balance.");
                return;
            }

            if(document.getElementById("type").value == "0"){
                alert("Please choose a payment option.");
                return;
            }

            var url = "{{Request::root()}}" + "/coupon/" + document.getElementById("amount").value + "/" + document.getElementById("type").value + "/" + document.getElementById("coupon").value;
            try{
                $.get( url , function( data ) {
                    try{data = JSON.parse(data);}
                    catch(e){}

                    alert(data.message);
                    document.getElementById("price").innerHTML = "$" + data.price;
                })
                .fail(function() {
                    alert( "Unable to apply the coupon! Please try again later." );
                });
            }

            catch(err){
                alert("Unable to apply the coupon! Please try again later.");
            }

        }

    </script>

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">
            <div class="container">
                <div class="row">
                    <h3>Top-up Your Balance</h3>
                    <br/>
                    {{ Form::open(array('action' => 'PaymentController@RedirectToPayment', 'id' => 'topup-form'))}}
                    <div class="form-group">
                        <div class="col-12">

                            <select  class="form-control" id="amount" name="amount" onchange="formChange()">
                                <option value="0" selected disabled>Amount?</option>
                                <option value="20">$20</option>
                                <option value="50">$50</option>
                                <option value="100">$100</option>
                                <option value="200">$200</option>
                                <option value="500">$500</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <select class="form-control" id="type" name="type" onchange="formChange()">
                                <option value="0" selected disabled>Payment System?</option>
                                @foreach ($paymentsystems as $paymentsystem)
                                <option value="{{$paymentsystem->system}}">@if($paymentsystem->system == "Payeer") Bitcoin @else {{$paymentsystem->system}} @endif</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="container-fluid no-padding">

                            <div class="col-lg-12 col-md-12 no-padding">
                                <div class="input-group text-center no-padding">
                                    <input
                                            type="text"
                                            class="form-control input-md"
                                            data-toggle="tooltip"
                                            name="coupon"
                                            id="coupon"
                                            placeholder="Promo Code? (Optional)"
                                            title="If you have a coupon code put it here"
                                            onfocus="this.select();"
                                            onmouseup="return false;"
                                    >

                        <span class="input-group-btn">
                            <input onclick="applyCoupon()" class="btn btn-primary btn-md" value="Redeem">
                        </span>

                                </div>
                            </div>
                        </div>
                    </div>

                    <br/>



                    <div class="form-group" id="final_amount">
						<span style="font-size:70%;"><span>Top-Up Amount:</span><span id="top" style="float:right;">$0</span></span><br/>
						
                        <span>Final Amount:</span><span id="price" style="float:right;">$0</span>
                    </div>



                    <div class="form-group">
                        <div class="col-12">
                            <button id="pay_button" type="submit" class="btn btn-lg btn-block btn-success" disabled>
                                Pay
                            </button>
                        </div>
                    </div>

                    {{ Form::close() }}

                    </div>
            </div>
        </div>
    </div>



@stop



@section('bottom')

@stop
