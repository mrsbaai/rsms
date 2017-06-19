@extends('layouts.app')

<title>Receive-SMS :: Recover</title>
@section('content')

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">

            <div class="row">
                {{ Form::open(array('action' => 'userController@recover', 'id' => 'recover-form'))}}
                <h2>Recover</h2>

                <p>Please enter your email and click send.</p>

                <input type="email" name="email" placeholder="nickname@example.com" required class="form-control input-lg" />
                <br/>

                <button type="submit" name="go" class="btn btn-lg btn-block btn-primary">Send</button>


                {{ Form::close() }}


            </div>
        </div> <!-- /.row-->

    </div>



@stop
