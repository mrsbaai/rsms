@extends('layouts.app')
@section('head')
    <title>Receive-SMS :: Login</title>
@stop

@section('content')

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">
            <div class="container">
                <div class="row">
                    <h2>Login</h2>
                    <form role="form" method="POST" action="{{ url('/login') }}" id="login-form">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-4 control-label">E-mail</label>

                            <div class="col-6">
                                <input id="email" type="text" class="form-control input-lg" name="email" value="{{ old('email') }}" required autofocus>
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
                                <input id="password" type="password" class="form-control input-lg" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5  nopadding">
                                <div class="form-group">

                                    <span class="checkbox nopadding">
                                            <label>
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                            </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 nopadding text-right">
                                <div class="form-group">
                                    <a class="btn-lg nopadding" href="{{ url('/password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>



                        <div class="form-group">
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-block btn-primary">
                                    Login
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 text-center">
                                OR
                            </div>
                        </div>
                    </form>
                        <div class="form-group">
                            <div class="col-12">
                                <a href="/register">
                                    <button class="btn btn-lg btn-block btn-success">
                                        Create an account
                                    </button>
                                </a>

                            </div>
                        </div>

                    

                </div>
            </div>

        </div> <!-- /.row-->



    </div>



@stop

