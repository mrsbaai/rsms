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
                            <th class="th-time">[Time]</th>
                            <th class="td-from">[From]</th>
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

        <script type="text/javascript">
            if ({{$messages->currentPage()}} == 1){
                var table = document.getElementById("messages-table");
                var emp = document.getElementById("emp");
                @if(count($messages) <> 0) var lastId = {{$lastMessage}};@else
                    lastId = 0;
                    table.style.display = 'none';
                    emp.style.visibility =  "visible";
                    @endif


                $(document).ready(function(){
                    refreshTable();
                });

                function refreshTable(){

                    var url = "../newmessages/" + lastId + @if(isset($current)) "/{{$current}}" @else "/all" @endif;

                    $.getJSON(url, function(data) {
                        jQuery.each(data, function(i, val) {


                            var row = table.insertRow(1);
                            var dateCell = row.insertCell(0);
                            var senderCell = row.insertCell(1);
                            var receiverCell = row.insertCell(2);
                            var messageCell = row.insertCell(3);

                            dateCell.className = "td-date";
                            senderCell.className = "td-sender";
                            receiverCell.className = "td-receiver";
                            messageCell.className = "td-message";

                            dateCell.setAttribute('data-title','[Date]');
                            senderCell.setAttribute('data-title','[Sender]');
                            receiverCell.setAttribute('data-title','[To]');
                            messageCell.setAttribute('data-title','[Message]');


                            var rows = document.getElementById('messages-table').getElementsByTagName("tr").length;
                            $('#messages-table').hide();
                            if (rows >= 17){
                                $('#messages-table tr:last').remove();
                            }

                            dateCell.innerHTML = "[" + val.date + "]";
                            senderCell.innerHTML = "[" + val.sender + "]";
                            receiverCell.innerHTML = "[" + val.receiver + "]";
                            messageCell.innerHTML = "[" + val.message + "]";
                            $('#messages-table').fadeToggle();

                            lastId = val.id;
                            table.style.display = '';
                            emp.style.display = 'none';


                        });


                    });

                    setTimeout(refreshTable,{{Config::get('settings.refreshWait')}});

                }
            }

        </script>





@stop
