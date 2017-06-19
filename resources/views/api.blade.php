
@extends('layouts.app')

@section('head')

    <title>Receive-SMS :: API</title>
@stop

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>Callback</h3>
            <p>Set up a Callback URL in your <a href="/account">account</a> settings. A request will be sent for each received SMS message. The request parameters are sent via a GET to your Callback URL.</p>
            <p>The request parameters sent via a GET to your URL include the following parameters:</p>

            <div class="container-fluid no-padding ">
                <div class=" col-lg-3 col-md-3 ">
                </div>
                <div class="col-lg-6 col-md-6  no-padding ">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Description</th>

                            </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <th>sender</th>
                                    <td>Sender number Ex: 1234567890</td>
                                </tr>
                                <tr>
                                    <th>receiver</th>
                                    <td>Recipient number Ex: 1234567890</td>
                                </tr>
                                <tr>
                                    <th>message</th>
                                    <td>Content of the message</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>
                </div>
                <div class=" col-lg-3 col-md-3 ">
                </div>
            </div>

        </div>

    </div>


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>Get Messages</h3>
            <p>This is a simple API service allowing to get previously received messages.</p>


            <div class="container-fluid no-padding ">
            <div class=" col-lg-2 col-md-2 ">
            </div>
            <div class="col-md-8  no-padding">
                <div class="form-group">
                    <input  class="form-control input-lg" value="http://receive-sms.com/api/{email}/{password}/{number}" readonly/>
                </div>

                <div class="container-fluid no-padding ">
                    <div class=" no-padding ">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Description</th>

                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <th>{email}</th>
                                    <td><b>Required</b>. Your login email Ex: john@gmail.com</td>
                                </tr>
                                <tr>
                                    <th>{password}</th>
                                    <td><b>Required</b>. Your login password Ex: 1234</td>
                                </tr>
                                <tr>
                                    <th>{number}</th>
                                    <td><b>Optional</b>. A recipient number Ex: 1234567890</td>
                                </tr>

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>




            </div>
            <div class=" col-lg-2 col-md-2 ">
            </div>

            </div>

            <h3>JSON Responce</h3>

            <div class="container-fluid no-padding ">
                <div class=" col-lg-2 col-md-2 ">
                </div>
                <div class="col-md-8  no-padding">
                    <div class="form-group">
                        <input  class="form-control input-lg" value="{&quot;from&quot;:&quot;from&quot;,&quot;to&quot;:&quot;to&quot;,&quot;message&quot;:&quot;message&quot;,&quot;date&quot;:&quot;YYYY-MM-DD HH:MM:SS&quot;}" readonly/>
                    </div>

                    <div class="container-fluid no-padding ">
                        <div class=" no-padding ">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Description</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <th>from</th>
                                        <td>Sender id Ex: 1234567890</td>
                                    </tr>
                                    <tr>
                                        <th>to</th>
                                        <td>Recipient Number Ex: 1234567890</td>
                                    </tr>
                                    <tr>
                                        <th>message</th>
                                        <td>Content of the message</td>
                                    </tr>
                                    <tr>
                                        <th>date</th>
                                        <td>Date when we have received the message expressed in UTC date Ex: 2013-11-15 14:34:40</td>
                                    </tr>

                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>




                </div>
                <div class=" col-lg-2 col-md-2 ">
                </div>

            </div>

            <h3>Example</h3>
            <p>Get all SMS received on the number "123456789":</p>
            <div class="container-fluid no-padding ">
                <div class=" col-lg-2 col-md-2 ">
                </div>

                <div class="col-md-8  no-padding">
                    <div class="form-group">
                        <input  class="form-control input-lg" value="CURL http://receive-sms.com/api/example@gmail.com/pass123/123456789" readonly/>
                    </div>

                </div>
                <div class=" col-lg-2 col-md-2 ">
                </div>

            </div>
            <p>JSON response:</p>
            <div class="form-group">
                        <textarea  class="form-control input-lg" readonly>[{"from":"123456789","to":"123456789","message":"Verification code is 23458","date":"2013-11-15 14:34:40"},{"from":"123456789","to":"123456789","message":"Verification code is 54356","date":"2013-11-15 15:23:31"}
                        </textarea>
            </div>


        </div>

    </div>



@stop



@section('bottom')

@stop
