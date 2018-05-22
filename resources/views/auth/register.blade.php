@extends('layouts.app')
@section('head')
    <title>Receive-SMS :: Register</title>
@stop
@section('content')

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">
            <div class="container">
                <div class="row">
                    <h1>Create an account</h1>
                    <form role="form" method="POST" action="{{ url('/register') }}" id="register-form">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-4 control-label">Name</label>

                            <div class="col-6">
                                <input id="name" type="text" class="form-control input-md" name="name" value="{{ old('name') }}" required autofocus>
                                <div class="help-block with-errors"></div>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-4 control-label">E-Mail Address</label>

                            <div class="col-6">
                                <input id="email" type="email" class="form-control input-md" name="email" value="{{ old('email') }}" required>
                                <div class="help-block with-errors"></div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-4 control-label">Password</label>

                            <div class="col-6">
                                <input id="password" type="password" class="form-control input-md" name="password" required>
                                <div class="help-block with-errors"></div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-4 control-label">Confirm Password</label>

                            <div class="col-6">
                                <input id="password_confirmation" type="password" class="form-control input-md" name="password_confirmation" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12 no-padding no-margin">
                            <div class="form-group no-padding no-margin">

                                <div id="messages"></div><br/>

                            </div>
                        </div>
                        <br/>

                        <div class="form-group">
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-block btn-success">
                                    Create
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div> <!-- /.row-->

    </div>



@endsection

@section('bottom')

    <script src="../js/register.validator.js"></script>


    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

@stop