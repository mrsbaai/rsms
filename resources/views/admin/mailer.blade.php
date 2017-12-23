
@extends('layouts.admin')




@section('head')

    <title>Receive-SMS :: Mailer</title>


    <script>
        function showPreview(){

            if (!document.getElementById('text1').value ){var text1 = "nothing"}else{var text1 = document.getElementById('text1').value}
            if (!document.getElementById('text2').value ){var text2 = "nothing"}else{var text2 = document.getElementById('text2').value}
            if (!document.getElementById('heading1').value ){var heading1 = "nothing"}else{var heading1 = document.getElementById('heading1').value}
            if (!document.getElementById('heading2').value ){var heading2 = "nothing"}else{var heading2 = document.getElementById('heading2').value}
            if (!document.getElementById('button').value ){var button = "nothing"}else{var button = document.getElementById('button').value}
            if (!document.getElementById('buttonURL').value ){var buttonURL = "nothing"}else{var buttonURL = document.getElementById('buttonURL').value}
            var url = "/admin/mailer/preview/" + btoa(text1) + "/" + btoa(text2) + "/" + btoa(heading1) + "/" + btoa(heading2) + "/" + btoa(button) + "/" + btoa(buttonURL);

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
                <input type="text" id="heading1" name="heading1" class="form-control" placeholder="Heading 1" onchange="showPreview()"><br>
                <textarea id="text1" name="text1" class="form-control" placeholder="Text 1" onchange="showPreview()"></textarea><br>
                <input type="text" id="heading2"  name="heading2" class="form-control" placeholder="Heading 2" onchange="showPreview()"><br>

                <input type="text" id="button"  name="button" class="form-control" placeholder="Button" onchange="showPreview()"><br>
                <input type="text" id="buttonURL" name="buttonURL" class="form-control" placeholder="Button URL" onchange="showPreview()"><br>
                <textarea id="text2" name="text2" class="form-control" placeholder="Text2" onchange="showPreview()"></textarea><br>

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
