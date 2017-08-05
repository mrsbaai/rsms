
@extends('layouts.admin')




@section('head')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


@section('content')
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
      $( function() {
          $( "#datepicker" ).datepicker();
      } );
  </script>
@stop
  <p>Date: <input type="text" id="datepicker"></p>

@stop



@section('bottom')

@stop
