
@extends('layouts.admin')




@section('head')

    <title>Fast Support</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
										<h3>
										{{$subject}}
										</h3><br/>
										<p>
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
										<br/><br/><br/>
										<p>
										Name:</b> {{$info[0]}}<br>
										Email:</b> {{$info[1]}}<br>
										Registred:</b> {{$info[6]}}<br>
										Numbers:</b> {{$info[7]}}<br>
										SMS Received:</b> {{$info[3]}}<br>
										Total Invested: ${{$info[2]}}<br>
										Cases:</b> {{$info[4]}}<br>
										Topups:</b> {{$info[5]}}<br>
										Payeer:</b> {{$info[9]}}<br>
										PayPal:</b> {{$info[10]}}<br>
										Supports:</b> {{$info[8]}}<br>
										</p>

    </div>



@stop
