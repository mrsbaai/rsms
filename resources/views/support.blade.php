@extends('layouts.app')


<title>Receive-SMS :: Support</title>

@section('content')

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">

            <div class="container">
                <div class="row">

                    <h2>Support {{@$result}}</h2>

                    <p class="lead">Please fill the form below and will get back to you as soon as possible.</p>

                    {{ Form::open(array('action' => 'supportController@send', 'id' => 'support-form'))}}
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="form_subject">Subject</label>
                                <input id="form_subject" type="text" name="subject" class="form-control" placeholder="Please enter a subject">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="form_message">Message</label>
                                <textarea id="form_message" name="message" class="form-control" placeholder="Please write your message" rows="4" required="required"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">

                                <div id="messages"></div>

                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <input type="submit" class="btn btn-success btn-send" value="SEND">
                        </div>

                        <div class="col-xs-12" style="height:30px;"></div>

                    </div>

                    {{ Form::close() }}


                </div>
            </div> <!-- /.row-->

        </div>
    </div>



@endsection

@section('bottom')

    <script src="../js/support.validator.js"></script>


    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

@stop