@component('mail::message')

<div class = "heading">
{{$heading1}}
</div>
<br/><br/>
{{$text1}}

<div class = "heading">
{{$heading2}}
</div>


@component('mail::button', ['url' => $buttonURL])
{{$button}}
@endcomponent

{{$text2}}

Regards,<br>
{{ config('app.name') }}
@endcomponent
