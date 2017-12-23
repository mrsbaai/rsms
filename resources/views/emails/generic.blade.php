@component('mail::message')

<div class = "heading">
{{$heading1}}
</div>
<br/><br/>
{{$text1}}
@if($button1)
<br/><br/>
@component('mail::button', ['url' => $buttonURL1])
{{$button1}}
@endcomponent
@endif

<br/><br/>
<img src="{{$img1}}"/>
s
<div class = "heading">
{{$heading2}}
</div>
<br/><br/>
{{$text2}}
<br/><br/>
@component('mail::button', ['url' => $buttonURL2])
    {{$button2}}
@endcomponent
<br/><br/>
<img src="{{$img2}}"/>

<div class = "heading">
    {{$heading3}}
</div>
<br/><br/>
{{$text3}}
<br/><br/>
@component('mail::button', ['url' => $buttonURL3])
    {{$button3}}
@endcomponent
<br/><br/>
<img src="{{$img2}}"/>

<div class = "heading">
    {{$heading4}}
</div>
<br/><br/>
{{$text4}}
<br/><br/>
Regards,<br>
{{ config('app.name') }}
@endcomponent
