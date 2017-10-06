
@extends('layouts.admin')




@section('head')

    <title>Support</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Respond To Users Requests</h1>

            <div class="container-fluid no-padding ">
                <div class="col-sm-12 no-padding ">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                @foreach($columns as $column)
                                    <th>{{ $column }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $id => $array)
                                <tr>
                                    @foreach($array as $content)
                                        <td>{{ $content }}</td>
                                    @endforeach
                                    <td style="width: 300px;">
                                        {{ Form::open(array('action' => 'adminController@sendResponse', 'id' => 'mailer-form'))}}
                                        <input type="hidden" name="email" value="{{$array['5']}}">
                                        <input type="hidden" name="name" value="{{$array['4']}}">
                                        <input type="hidden" name="subject" value="{{$array['3']}}">
                                        <input type="hidden" name="id" value="{{$array['0']}}">
                                        <textarea id="response" type="text" name="response" class="form-control"></textarea>
                                        <br/>
                                        <input type="submit" class="btn btn-primary btn-send " value="Send">
                                        <a class="float:right;"><a href="/admin/support/delete/{{$array['0']}}">Delete</a></span>
                                        {{ Form::close() }}

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>




@stop



@section('bottom')

@stop
