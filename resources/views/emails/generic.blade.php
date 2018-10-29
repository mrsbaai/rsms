@component('mail::message')
@if($heading1)
<div class = "heading">
{{$heading1}}
</div>
@endif
@if($text1)
<br/>
{{$text1}}
<br/>
@endif
@if($button1)
@component('mail::button', ['url' => $buttonURL1])
{{$button1}}
@endcomponent
@endif
@if($img1)
<br/>
<img src="{{$img1}}"/>
<br/>
@endif
@if($heading2)
<div class = "heading">
{{$heading2}}
</div>
@endif
@if($text2)
<br/>
{{$text2}}
<br/>
@endif
@if($button2)
@component('mail::button', ['url' => $buttonURL2])
{{$button2}}
@endcomponent
@endif
@if($heading3)
<div class = "heading">
{{$heading3}}
</div>
@endif
@if($text3)
<br/>
{{$text3}}
<br/>
@endif
@if($button3)
@component('mail::button', ['url' => $buttonURL3])
{{$button3}}
@endcomponent
@endif
@if($heading4)
<div class = "heading">
{{$heading4}}
</div>
<br/>
@endif
@if($text4)
<br/>
{{$text4}}
<br/>
@endif
<br/>
@if($img2)
<br/>
<center><img src="{{$img2}}"/></center>
<br/>
@endif
<br/>
Regards,
<br/>
{{ config('app.name') }} Team.
<br/>
<br/>

<div style="text-align: center;">
<a style="font-size: 90%;" href="https://receive-sms.com/unsubscribe/{{$email}}">Unsubscribe</a>
</div>



@endcomponent
