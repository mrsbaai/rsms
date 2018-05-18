
@extends('layouts.admin')




@section('head')

    <title>Support</title>

@stop

@section('content')
    <div class="container width-fix col-sm-12">
        <div class="jumbotron welcome-texture">
            <h1>Respond To Users Requests</h1>

            <p>We have experienced a high volume of inquires and regret that we have not addressed your case sooner.</p>
            <p>We can only provide US numbers at the moment.</p>
            <p>Sending SMS messages is not possible at the moment.</p>
            <p>Paid numbers can receive SMS messages from anywhere. Demo numbers Can fail some times because of high volume usage.</p>
            <p>We can't provide that amount of numbers at the current time.</p>
            <p>Sorry we can't provide back expired numbers.</p>
            <p>I don't fully understand your request. If you're interested in a US phone number, use this coupon code "WINTER" with all your tom ups to get a 50% discount.</p>
            <p>If you are interested in a large amount of numbers, you might find what you're looking for here: https://sms-verification.net</p>
            <div class="container-fluid no-padding ">
                <div class="col-sm-12 no-padding ">
                    <div class="no-more-tables">
                        <table class="table table-condensed">

                            <tbody>
                            @foreach($rows as $id => $array)
                                <tr>
									<td style="width: 50px;">
									@if ($array['1'])
									Support: 
									@else
									Contact: 
									@endif
									
									</td>
                                    <td style="width: 100px;">{{$array['3']}}</td>
									
									<td style="width: 300px;">{{$array['6']}}</td>
                                    <td style="width: 300px;">
                                        {{ Form::open(array('action' => 'adminController@sendResponse', 'id' => 'mailer-form'))}}
                                        <input type="hidden" name="email" value="{{$array['5']}}">
                                        <input type="hidden" name="name" value="{{$array['4']}}">
                                        <input type="hidden" name="subject" value="{{$array['3']}}">
                                        <input type="hidden" name="id" value="{{$array['0']}}">
                                        <textarea id="response" type="text" name="response" class="form-control" style="width: 280px; height: 200px;">We have experienced a high volume of inquires and regret that we have not addressed your case sooner.</textarea>
                                        <br/>
                                        <input type="submit" class="btn btn-primary btn-send " value="Send">
                                        <a href="/fast/support/{{$array['0']}}">View</a> | 
                                        <a href="/admin/support/delete/{{$array['0']}}">Delete</a>
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
