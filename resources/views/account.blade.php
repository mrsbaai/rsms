
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



            </div>

        </div>

    </div>



@stop



@section('bottom')

    <script src="../js/account.validator.js"></script>


    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

@stop
