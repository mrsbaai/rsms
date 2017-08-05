
@extends('layouts.admin')




@section('head')

    <title>Receive-SMS :: Administration</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>DATA 1</h1>

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
