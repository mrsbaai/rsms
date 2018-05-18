
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
										<h3>
										{{$info}}
										</h3>

    </div>



@stop
