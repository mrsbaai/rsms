
@extends('layouts.admin')




@section('head')

    <title>Fast Support</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">

										<h3>
										{{$subject}} - ({{$date}})
										</h3><br/>
										<p class="lead">
										{{$message}}
										</p>
                                        {{ Form::open(array('action' => 'adminController@sendResponse', 'id' => 'fastsupport-form'))}}
                                        <input type="hidden" name="email" value="{{$email}}">
                                        <input type="hidden" name="name" value="{{$name}}">
                                        <input type="hidden" name="subject" value="{{$subject}}">
                                        <input type="hidden" name="id" value="{{$id}}">
                                        <textarea id="response" type="text" name="response" class="form-control"></textarea>
                                        <br/>
                                        <input type="submit" class="btn btn-primary btn-send " value="Send">
                                        <a class="float:right;"><a href="/admin/support/delete/{{$id}}">Ignore</a></span>
                                        {{ Form::close() }}
										<br/><br/>
										<p class="lead">
										<span style="float:left;">
											
											Balance: {{$info[11]}}<br>
											Total Invested: ${{$info[2]}}<br>
											Cases: {{$info[4]}}<br>
											Topups: {{$info[5]}}<br>
											Payeer: {{$info[9]}}<br>
											PayPal: {{$info[10]}}<br>
											Supports: {{$info[8]}}<br>
										</span>
										<span style="float:right;">
											Name: {{$info[0]}}<br>
											Email: {{$info[1]}}<br>
											Registred: {{$info[6]}}<br>
											Numbers: {{$info[7]}}<br>
											SMS Received: {{$info[3]}}<br>
										</span>

										</p>

    </div>



@stop
