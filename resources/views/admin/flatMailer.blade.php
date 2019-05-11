
@extends('layouts.admin')




@section('head')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <title>Receive-SMS :: Flat Mailer</title>


@stop

@section('content')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#datepicker" ).datepicker();
        } );
    </script>
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Promo mail</h1>
            {{ Form::open(array('action' => 'MaillingController@makeFlatList', 'id' => 'mailer-form'))}}
                <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject" required="required"><br>
                <input type="text" id="from_name" name="from_name" class="form-control" placeholder="From name" ><br>
                <input type="text" id="from_email" name="from_email" class="form-control" placeholder="From email" ><br>

                <textarea id="html" name="html" class="form-control" placeholder="html"></textarea><br>

                <input type="text" id="datepicker" name="sendingdate" class="form-control" placeholder="Sending date"><br>

            <div class="form-group">
                <label for="list">Select Email List:</label>
                <select class="form-control" id="list" name="list">
                    <option>All Subscribers and Users</option>
                    <option>All Subscribers</option>
                    <option>All Users</option>
                    <option>Subscribers Didn't register</option>
                    <option>Users Topped Up</option>
                    <option>Users Didn't Top Up</option>
                    <option>Users With Numbers</option>
                    <option>Users Without Numbers</option>
                </select>
            </div>

            <br/>
            <input type="checkbox" name="is_test" value="Send a test only?"><br>
            <input type="text" id="test_email" name="test_email" class="form-control" placeholder="Test email"><br>

            <div class="text-right">
                    <input type="submit" class="btn btn-lg btn-success btn-send" value="Send">
                </div>

            {{ Form::close() }}
			
			

                    
           
        </div>
    </div>




@stop



@section('bottom')

@stop
