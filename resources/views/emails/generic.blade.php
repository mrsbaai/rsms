@component('mail::message')
@if($heading1)
<div class = "heading">
{{$heading1}}
</div>
<br/>

@endif
@if($text1)
<br/>

<br/>
{{$text1}}
<br/>

<br/>
@endif
@if($button1)
<br/>

<br/>
@component('mail::button', ['url' => $buttonURL1])
{{$button1}}
@endcomponent
<br/>

@endif
@if($img1)
<br/>

<br/>
<img src="{{$img1}}"/>
<br/>

<br/>
@endif
@if($heading2)
<br/>

<div class = "heading">
{{$heading2}}
</div>
<br/>

@endif
@if($text2)
<br/>

<br/>
{{$text2}}
<br/>

@endif
@if($button2)
<br/>

<br/>
@component('mail::button', ['url' => $buttonURL2])
{{$button2}}
@endcomponent
<br/>

@endif
@if($heading3)
<br/>

<div class = "heading">
{{$heading3}}
</div>
<br/>

@endif
@if($text3)
<br/>

<br/>
{{$text3}}
<br/>

@endif
@if($button3)
<br/>

<br/>
@component('mail::button', ['url' => $buttonURL3])
{{$button3}}
@endcomponent
<br/>

@endif
@if($img2)
<br/>

<br/>
<img src="{{$img2}}"/>
<br/>

<br/>

@endif
@if($heading4)
<br/>

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

<br/>

Regards,
<br/>
{{ config('app.name') }} Team.
@endcomponent
