@extends('layouts.app')

@section('head')
    <title>Receive-SMS :: FAQs</title>
@stop

@section('content')

    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture col-sm-12">

            <div class="container">
                <div class="row">

                    <h3>Frequently Asked Questions</h3>
                    <p><b>What is this?</b></p>
                    <p>You will find on this website phone numbers. You can use these numbers to anonymously receive SMS messages.</p>
                    <p><b>Can I use this with online registrations?</b></p>
                    <p>Yes you can.</p>
                    <p><b>What number should I use?</b></p>
                    <p>You can use one of the demo numbers from the home page, or register a free account and buy private numbers. </p>
                    <p><b>Where can I read inbound messages?</b></p>
                    <p>For the demo phone numbers, inbound messages are shown on the home page. If you have private numbers you need to login to your account to see inbound messages</p>
                    <p><b>How to get more information?</b></p>
                    <p>If you have any question or concerns it's simple - just <a href="/contact">Contact Us</a>.</p>


                </div>

            </div>
        </div>
    </div>



@stop
@section('bottom')
    @if(!Auth::check())
        <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <a href="/register">
                <button class="btn btn-lg btn-success">
                    Create Your Private Inbox
                </button>
            </a>
        </div>
        <br><br>

    @endif

@stop