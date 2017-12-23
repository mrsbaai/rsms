@component('mail::message')
@if($heading1)
<div class = "heading">
{{$heading1}}
</div>
@endif
@if($text1)



{{$text1}}
@endif
@if($button1)



@component('mail::button', ['url' => $buttonURL1])
{{$button1}}
@endcomponent
@endif
@if($img1)



<img src="{{$img1}}"/>



@endif
@if($heading2)
<div class = "heading">
{{$heading2}}
</div>
@endif
@if($text2)



{{$text2}}
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



{{$text3}}
@endif
@if($button3)



@component('mail::button', ['url' => $buttonURL3])
{{$button3}}
@endcomponent
@endif
@if($img2)



<img src="{{$img2}}"/>




@endif
@if($heading4)
<div class = "heading">
{{$heading4}}
</div>
@endif
@if($text4)



{{$text4}}


@endif




Regards,

{{ config('app.name') }} Team
@endcomponent
