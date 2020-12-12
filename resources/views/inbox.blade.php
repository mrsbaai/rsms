
@extends('layouts.app')
@include('buy')
@include('numbers_table')
@include('messages_table')

@section('head')
    <title>Receive-SMS :: Inbox</title>
@stop

@section('content')

    @if($noNumbers)
        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>Hello {{Auth::user()->name}}!</h2>
                <p>Welcome to your Inbox. Now you can <a href="https://receive-sms.com/topup">Top-up</a> your balance and <a href="https://receive-sms.com/numbers">add numbers</a> right away!</p>
            </div>

        </div>

    @else
        <script>
            function go(){
                var e = document.getElementById("number");
                var number = e.options[e.selectedIndex].value;
                window.location = "../inbox/" + number;
            }
        </script>

        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <div class=" col-sm-9 no-padding"> <h3> <a class="btn btn-small btn-info btn-send" href="/replace/">Replace with a diferent number</a> [{{$current}}] Inbound Messages  - <span class="text-success">Live</span></h3></div>
                <div class=" col-sm-3 no-padding" style="padding-top: 18px;">
                    <div class="col-sm-12 no-padding">
                        <select  class="form-control" id="number" onchange="go()">
                            <option value="" @if($current == "all") selected @endif>All
                            @foreach ($numbers as $number)
                                <option value="{{$number->number}}" @if($number->number == $current) selected @endif>{{$number->number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <br/> <br/>

                @yield('messages_table')
            </div>

        </div>


    @endif




@stop



@section('bottom')

@stop
