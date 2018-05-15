@extends('layouts.app')


@section('head')
    <title>Receive-SMS :: Unsubscribe</title>
@stop

@section('content')

    <div class="container width-fix small-box text-center">
        <div class="jumbotron welcome-texture">

            <div class="container">
                <div class="row">

                    <h2 class="text-danger">We're sorry to see you go :(</h2>


                    {{ Form::open(array('action' => 'SubscribersController@unsubscribe', 'id' => 'unsubscribe'))}}


                        <div class="col-md-12">
                            <div class="form-group">
                                <input id="email" type="email" name="email" class="form-control" placeholder="Enter Your Email Here" required="required" value="{{$email}}">
                            </div>
                        </div>

                        <div class="col-md-12t">
                            <input type="submit" class="btn btn-success btn-send" value="Unsubscribe">
                        </div>

                        <div class="col-xs-12" style="height:30px;"></div>

                    </div>

                    {{ Form::close() }}


                </div>
            </div> <!-- /.row-->

        </div>
    </div>



        @stop
        @section('bottom')

            <script src="../js/contact.validator.js"></script>


            <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
            <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

        @stop