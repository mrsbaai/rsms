
@extends('layouts.admin')




@section('head')

    <title>Support</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Respond To Users Requests</h1>

            <p>We have experienced a high volume of inquires and regret that we have not addressed your case sooner.</p>
            <p>We only provide US numbers at the moment. You might find what you're looking for here: https://sms-verification.net</p>
            <p>Sending SMS messages is not possible at the moment. You might find what you're looking for here: https://sms-verification.net</p>
            <p>Paid numbers can receive SMS messages from anywhere. Demo numbers Can fail some times because of high volume usage.</p>
            <p>We can't provide that amount of numbers at the current time. You might find what you're looking for here: https://sms-verification.net</p>
            <p>Sorry we can't provide back expired numbers.</p>
            <p>I don't fully understand your request. If you're interested in a US phone number, use this coupon code "WINTER" with all your tom ups to get a 50% discount.</p>

            <div class="container-fluid no-padding ">
                <div class="col-sm-12 no-padding ">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th style="width: 50px;">id</th>
                                <th style="width: 50px;">is_support</th>
                                <th style="width: 50px;">created_at</th>
                                <th style="width: 50px;">subject</th>
                                <th style="width: 50px;">name</th>
                                <th style="width: 50px;">email</th>
                                <th style="width: 700px;">message</th>
                                <th style="width: 700px;">Respond</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $id => $array)
                                <tr>
                                    @foreach($array as $content)
                                        <td>{{ $content }}</td>
                                    @endforeach
                                    <td style="width: 900px;">
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
