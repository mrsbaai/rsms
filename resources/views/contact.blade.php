@extends('layouts.app')


@section('head')
	@include('recaptcha::script')
    <title>Receive-SMS :: Contact</title>
@stop

@section('content')

    <div class="container width-fix small-box">
        <div class="jumbotron welcome-texture">

            <div class="container">
                <div class="row">

                    <h2>Contact Us {{@$result}}</h2>

     								@if ($errors->any())
											<p class="lead" style="color:red;">
												@foreach ($errors->all() as $error)
												{{ $error }}
												@endforeach
											</p>
				
									@endif

								
                    <p class="lead">Please fill the form below and will get back to you as soon as possible.</p>
					


                    {{ Form::open(array('action' => 'contactController@store', 'id' => 'contact-form'))}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" name="name" class="form-control" placeholder="Please enter your full name" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="form_email">Email</label>
                                <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email" required="required">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="form_subject">Subject</label>
                                <input id="form_subject" type="text" name="subject" class="form-control" placeholder="Please enter a subject" required="required">
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
                            @include('recaptcha::widget')
                            </div>
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

    <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h2 style="color:white;">
            <a style="color:white; " href="https://sms-verification.net" target="_blank" title="SMS Verification">Interested In 200+ Numbers? Checkout SMS-Verification.net</a>
        </h2>
    </div>


        @stop
        @section('bottom')

            <script src="../js/contact.validator.js"></script>


            <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"/>
            <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"> </script>

        @stop