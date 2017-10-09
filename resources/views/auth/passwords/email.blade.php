@extends('layouts.app')

@section('head')
    <title>Receive-SMS :: Reset</title>
@stop
@section('content')
<div class="container width-fix small-box">
    <div class="jumbotron welcome-texture">

        @if (session('status'))
            <div class="container alert alert-success">
                    {{ session('status') }}
            </div>

        @else
            <div class="container">
                <div class="row">




                    <form role="form" method="POST" action="{{ url('/password/email') }}" id="reset-form">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-12">
                                <h2>Reset Password</h2>
                                <input id="email" type="email" class="form-control input-lg" placeholder="E-Mail Address" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-block btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>

                        <div class="col-md-12 no-padding no-margin" hidden>
                            <div class="form-group no-padding no-margin">

                                <div id="messages"></div>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

        @endif


    </div>
</div>
@endsection