
@extends('layouts.admin')




@section('head')

    <title>Receive-SMS :: Administration</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Sources</h1>
            {{ Form::open(array('action' => 'adminController@showSources', 'id' => 'sources-form'))}}
            <div class="form-group">
                <label for="type">Action:</label>
                <select class="form-control" id="type" name="type">
                    <option>Topups</option>
                    <option>Subscribes</option>
                    <option>Registrations</option>
                    <option>Renews</option>
                    <option>Spending</option>
                </select>
            </div>

            <div class="form-group">
                <label for="period">Show Period:</label>
                <select class="form-control" id="period" name="period">
                    <option>24h</option>
                    <option>7 Days</option>
                    <option>1 Month</option>
                    <option>3 Months</option>
                    <option>All</option>
                </select>
            </div>
            <div class="text-right">
                <input type="submit" class="btn btn-lg btn-success btn-send" value="SHOW">
            </div>
            {{ Form::close() }}
        </div>
    </div>


    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>{{$type}} - {{$period}}</h1>
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
