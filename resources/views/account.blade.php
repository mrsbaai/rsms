
@extends('layouts.app')

@section('title', 'Receive SMS Online')

<title>Receive-SMS :: Account</title>

@section('content')


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <div class="row">





                <div class="row">

                    <div class="col-md-6">
                        {{ Form::open(array('action' => 'userController@updateInfo', 'id' => 'account-form'))}}
                        <div class="col-md-12">
                            <h3>Account Info <span class="{{@$account_form_color}}">{{@$account_form_result}}</span></h3>
                            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input id="name" type="text" name="name" class="form-control" placeholder="Your Name Here" value="{{Auth::user()->name}}" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input id="email" type="email" name="email" class="form-control" placeholder="Your Email Here"  value="{{Auth::user()->email}}" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="callback">Callback URL (Optional):</label>
                                <input id="callback" type="text" name="callback" class="form-control" placeholder="Callback URL" value="{{Auth::user()->callback_url}}">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>


                        <div class="col-md-12 text-center">
                            <input type="submit" class="btn btn-primary btn-send" value="Update">
                        </div>
                        <div class="col-md-12">
                            <div id="accountmessages"></div>
                        </div>

                        {{ Form::close() }}


                    </div>

                    <div class="col-md-6">
                        {{ Form::open(array('action' => 'userController@updateInfo', 'id' => 'password-form'))}}
                        <div class="col-md-12">

                            <h3>Change Password  <span class="{{@$password_form_color}}">{{@$password_form_result}}</span></h3>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="current_password">Current Password:</label>
                                <input id="current_password" type="password" name="current_password" class="form-control" placeholder="Your Current Password" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input id="new_password" type="password" name="new_password" class="form-control" placeholder="Your New Password" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="repeat_password">Confirm Password:</label>
                                <input id="repeat_password" type="password" name="repeat_password" class="form-control" placeholder="Type Your New Password again" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>


                        <div class="col-md-12 text-center">
                            <input type="submit" class="btn btn-primary btn-send" value="Change">
                        </div>
                        <div class="col-md-12">
                            <div id="passwordmessages"></div>
                        </div>
                        {{ Form::close() }}
                    </div>



                    <div class="col-xs-12" style="height:30px;"></div>

                </div>
				
				<div class="row">
				  <div class="col-md-12 text-center">
                     <a class="btn btn-danger btn-send" href="/delete" onclick="return confirm('This will delete your account permanently. Are you sure?')">Delete Account</a>
                  </div>
				</div>



            </div>

        </div>

    </div>


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h3>A Simple Callback Service</h3>
            <p>Set up a Callback URL above. A request will be sent for each received SMS message. The request parameters are sent via a GET to your Callback URL.</p>
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



@stop



@section('bottom')

    <script src="../js/account.validator.js"></script>


    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

@stop
