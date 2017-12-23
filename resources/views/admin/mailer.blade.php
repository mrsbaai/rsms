
@extends('layouts.admin')




@section('head')

    <title>Receive-SMS :: Mailer</title>


    <script>
        function showPreview(){

            if (!document.getElementById('content').value ){var content = "nothing"}else{var content = document.getElementById('content').value}
            var url = "/admin/mailer/preview/" + btoa(content);

            document.getElementById('previewIframe').src = url;
        }
    </script>


@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Promo mail</h1>
            {{ Form::open(array('action' => 'adminController@send', 'id' => 'mailer-form'))}}
                <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject" required="required"><br>
                <textarea id="text1" name="text1" class="form-control" style="height: 700px;" placeholder="Text 1" onchange="showPreview()"></textarea><br>

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


                <div class="text-right">
                    <input type="submit" class="btn btn-lg btn-success btn-send" value="SEND">
                </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Preview</h1>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe id="previewIframe" class="embed-responsive-item" src="about:blank"></iframe>
            </div>
        </div>
    </div>





@stop



@section('bottom')

@stop
