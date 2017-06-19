@section('numbers_table')
    <div class="container-fluid no-padding ">
        <div class=" col-lg-3 col-md-3 ">
        </div>
        <div class="col-lg-6 col-md-6  no-padding ">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>[Number]</th>
                        <th>[Country]</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($numbers as $number)
                        <tr>
                            @if(Auth::check())
                                <td><b>[<a style="color:white;" href="inbox/{{$number->number}}" title="Click To See Inbound SMS">{{$number->number}}</a>]</b></td>
                            @else
                                <td><b>[<a style="color:white;" href="/{{$number->number}}" title="Click To See Inbound SMS">{{$number->number}}</a>]</b></td>
                            @endif

                            <td>[{{$number->country}}]</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

            </div>
        </div>
        <div class=" col-lg-3 col-md-3 ">
        </div>
    </div>
@stop
