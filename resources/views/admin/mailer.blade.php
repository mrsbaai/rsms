
@extends('layouts.admin')




@section('head')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <title>Receive-SMS :: Mailer</title>


    <script>
        function showPreview(){
			if (!document.getElementById('subject').value ){var subject = "nothing"}else{var subject = document.getElementById('subject').value}
            
            if (!document.getElementById('text1').value ){var text1 = "nothing"}else{var text1 = document.getElementById('text1').value}
            if (!document.getElementById('text2').value ){var text2 = "nothing"}else{var text2 = document.getElementById('text2').value}
            if (!document.getElementById('heading1').value ){var heading1 = "nothing"}else{var heading1 = document.getElementById('heading1').value}
            if (!document.getElementById('heading2').value ){var heading2 = "nothing"}else{var heading2 = document.getElementById('heading2').value}

            if (!document.getElementById('text3').value ){var text3 = "nothing"}else{var text3 = document.getElementById('text3').value}
            if (!document.getElementById('text4').value ){var text4 = "nothing"}else{var text4 = document.getElementById('text4').value}
            if (!document.getElementById('heading3').value ){var heading3 = "nothing"}else{var heading3 = document.getElementById('heading3').value}
            if (!document.getElementById('heading4').value ){var heading4 = "nothing"}else{var heading4 = document.getElementById('heading4').value}

            if (!document.getElementById('button1').value ){var button1 = "nothing"}else{var button1 = document.getElementById('button1').value}
            if (!document.getElementById('buttonURL1').value ){var buttonURL1 = "nothing"}else{var buttonURL1 = document.getElementById('buttonURL1').value}
            if (!document.getElementById('button2').value ){var button2 = "nothing"}else{var button2 = document.getElementById('button2').value}
            if (!document.getElementById('buttonURL3').value ){var buttonURL3 = "nothing"}else{var buttonURL3 = document.getElementById('buttonURL3').value}
            if (!document.getElementById('button3').value ){var button3 = "nothing"}else{var button3 = document.getElementById('button3').value}
            if (!document.getElementById('buttonURL2').value ){var buttonURL2 = "nothing"}else{var buttonURL2 = document.getElementById('buttonURL2').value}


            if (!document.getElementById('img1').value ){var img1 = "nothing"}else{var img1 = document.getElementById('img1').value}
            if (!document.getElementById('img2').value ){var img2 = "nothing"}else{var img2 = document.getElementById('img2').value}


            var url = "/admin/mailer/preview/" + encodeURIComponent(text1) + "/" + encodeURIComponent(text2) + "/" + encodeURIComponent(text3) + "/" + encodeURIComponent(text4) + "/" + encodeURIComponent(heading1) + "/" + encodeURIComponent(heading2) + "/" + encodeURIComponent(heading3) + "/" + encodeURIComponent(heading4) + "/" + encodeURIComponent(img1) + "/" + encodeURIComponent(img2) + "/" + encodeURIComponent(button1) + "/" + encodeURIComponent(button2) + "/" + encodeURIComponent(button3) + "/" + btoa(buttonURL1) + "/" + btoa(buttonURL2) + "/" + btoa(buttonURL3);
            document.getElementById('previewIframe').src = url;
			
			var testurl = "/admin/sendtest/" + encodeURIComponent(text1) + "/" + encodeURIComponent(text2) + "/" + encodeURIComponent(text3) + "/" + encodeURIComponent(text4) + "/" + encodeURIComponent(heading1) + "/" + encodeURIComponent(heading2) + "/" + encodeURIComponent(heading3) + "/" + encodeURIComponent(heading4) + "/" + encodeURIComponent(img1) + "/" + encodeURIComponent(img2) + "/" + encodeURIComponent(button1) + "/" + encodeURIComponent(button2) + "/" + encodeURIComponent(button3) + "/" + btoa(buttonURL1) + "/" + btoa(buttonURL2) + "/" + btoa(buttonURL3)+ "/" + encodeURIComponent(subject);
           
        }
    </script>


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
            {{ Form::open(array('action' => 'MaillingController@makeList', 'id' => 'mailer-form'))}}
                <input type="text" name="subject" class="form-control" placeholder="Subject" required="required"><br>

                <input type="text" id="heading1" name="heading1" class="form-control" placeholder="Heading 1" onchange="showPreview()"><br>
                <textarea id="text1" name="text1" class="form-control" placeholder="Text 1" onchange="showPreview()"></textarea><br>
                <input type="text" id="button1"  name="button1" class="form-control" placeholder="Button 1" onchange="showPreview()"><br>
                <input type="text" id="buttonURL1" name="buttonURL1" class="form-control" placeholder="Button URL 1" onchange="showPreview()"><br>
                <input type="text" id="img1" name="img1" class="form-control" placeholder="img1" onchange="showPreview()"><br>

                <input type="text" id="heading2"  name="heading2" class="form-control" placeholder="Heading 2" onchange="showPreview()"><br>
                <textarea id="text2" name="text2" class="form-control" placeholder="Text 2" onchange="showPreview()"></textarea><br>
                <input type="text" id="button2"  name="button2" class="form-control" placeholder="Button 2" onchange="showPreview()"><br>
                <input type="text" id="buttonURL2" name="buttonURL2" class="form-control" placeholder="Button URL 2" onchange="showPreview()"><br>

                <input type="text" id="heading3"  name="heading3" class="form-control" placeholder="Heading 3" onchange="showPreview()"><br>
                <textarea id="text3" name="text3" class="form-control" placeholder="Text 3" onchange="showPreview()"></textarea><br>
                <input type="text" id="button3"  name="button3" class="form-control" placeholder="Button 3" onchange="showPreview()"><br>
                <input type="text" id="buttonURL3" name="buttonURL3" class="form-control" placeholder="Button URL 3" onchange="showPreview()"><br>

                <input type="text" id="img2" name="img2" class="form-control" placeholder="img2" onchange="showPreview()"><br>

                <input type="text" id="heading4" name="heading4" class="form-control" placeholder="Heading 4" onchange="showPreview()"><br>
                <textarea id="text4" name="text4" class="form-control" placeholder="Text 4" onchange="showPreview()"></textarea><br>
                <input type="text" id="datepicker" name="sendingdate" class="form-control" placeholder="Sending date" required="required"><br>

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
				<a id = "sendTest" target="_BLANK" class="btn btn-lg btn-info btn-send" href="">Send a test</a>
                    <input type="submit" class="btn btn-lg btn-success btn-send" value="Make Pending List">
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
