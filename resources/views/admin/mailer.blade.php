
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
                <input type="text" name="subject" class="form-control" placeholder="Subject" required="required"><br>
                <input type="text" id="heading1" name="heading1" class="form-control" placeholder="Heading 1" required="required" onchange="showPreview()"><br>
                <textarea id="text1" name="text1" class="form-control" placeholder="Text 1" required="required" onchange="showPreview()"></textarea><br>
                <input type="text" id="heading2"  name="heading2" class="form-control" placeholder="Heading 2" required="required" onchange="showPreview()"><br>

                <input type="text" id="button"  name="button" class="form-control" placeholder="Button" required="required" onchange="showPreview()"><br>
                <input type="text" id="buttonURL" name="buttonURL" class="form-control" placeholder="Button URL" required="required" onchange="showPreview()"><br>
                <textarea id="text2" name="text2" class="form-control" placeholder="Text2" required="required" onchange="showPreview()"></textarea><br>
            <br/>
            <div class="col-sm-6">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="all">
                        All
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="subscribers">
                        All Subscribers
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="users">
                        ALL Users
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="topup">
                        Users Topped Up
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="notopup">
                        Users Didn't Top Up
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="withnumbers">
                        Users With Numbers
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="withoutnumbers">
                        Users Without Numbers
                    </label>
                </div>
            </div>



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
