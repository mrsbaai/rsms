@component('mail::message')
    @if($heading1)
        <div class = "heading">
            {{$heading1}}
        </div>
    @endif
    @if($text1)
        <br/><br/>
        {{$text1}}
    @endif
    @if($button1)
        <br/><br/>
        @component('mail::button', ['url' => $buttonURL1])
            {{$button1}}
        @endcomponent
    @endif
    @if($img1)
        <br/><br/>
        <img src="{{$img1}}"/>
    @endif
    @if($heading2)
        <div class = "heading">
            {{$heading2}}
        </div>
    @endif
    @if($text2)
        <br/><br/>
        {{$text2}}
    @endif
    @if($button2)
        <br/><br/>
        @component('mail::button', ['url' => $buttonURL2])
            {{$button2}}
        @endcomponent
    @endif
    @if($img2)
        <br/><br/>
        <img src="{{$img2}}"/>
    @endif
    @if($heading3)
        <div class = "heading">
            {{$heading3}}
        </div>
    @endif
    @if($text3)
        <br/><br/>
        {{$text3}}
    @endif
    @if($button3)
        <br/><br/>
        @component('mail::button', ['url' => $buttonURL3])
            {{$button3}}
        @endcomponent
    @endif
    @if($img3)
        <br/><br/>
        <img src="{{$img3}}"/>
    @endif
    @if($heading4)
        <div class = "heading">
            {{$heading4}}
        </div>
    @endif
    @if($text4)
        <br/><br/>
        {{$text4}}
    @endif
<br/><br/>
Regards,<br>
{{ config('app.name') }}
@endcomponent
