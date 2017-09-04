@section('messages_table')

    @if(count($messages) == 0)
        <p id="emp">Empty.</p>
    @endif
        <div class="container-fluid nopadding">
            <div id="messages-table-container" class="nopadding">
                <div id="no-more-tables">


                    <table id="messages-table" class="col-md-12 table messages-table table-condensed cf">
                        <thead class="cf">
                        <tr>
                            <th>[Time]</th>
                            <th>[From]</th>
                            <th>[To]</th>
                            <th>[Message]</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($messages as $message)
                            <tr>
                                <td data-title="[Date]" class="td-date">[{{$message->date}} UTC]</td>
                                <td data-title="[From]" class="td-from">[{{$message->sender}}]</td>
                                @if(Auth::check())
                                    <td data-title="[To]" class="td-to">[<a title="Click to see SMS received on {{$message->receiver}}" href="/inbox/{{$message->receiver}}" style="color:white;">{{$message->receiver}}</a>]</td>

                                @else
                                    <td data-title="[To]" class="td-to">[<a title="Click to see SMS received on {{$message->receiver}}" href="/{{$message->receiver}}" style="color:white;">{{$message->receiver}}</a>]</td>
                                @endif

                                <td data-title="[Message]" class="td-message">[{{$message->message}}]</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>



                </div>
            </div>
            <center>{{ $messages->links() }}</center>
        </div>


@stop
