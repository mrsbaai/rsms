
@extends('layouts.app')
@include('numbers_table')
@include('messages_table')


@section('share_buttons')



@stop

@section('head')


    <!-- Open Graph data -->
    <meta property="og:title" content="Receive SMS Online" />
    <meta property="og:type" content="WebSite" />
    <meta property="og:url" content="https://{{config::get('settings.domain')}}" />
    <meta property="og:image" content="{{config::get('settings.thumbnail')}}" />
    <meta property="og:description" content="Receive SMS Online. Use our online numbers with your online registrations. Avoid any unwanted messages on your personal cellphone." />
    <meta property="og:site_name" content="Receive-SMS" />

    <meta name="keywords" content="receive, sms, online">
    <meta name="description" content="Receive SMS Online. Use our online numbers with your online registrations. Avoid any unwanted messages on your personal cellphone.">
    <title>Receive SMS Online</title>
	<link rel="stylesheet" href="{{ URL::asset('css/share.css') }}">

@stop

@section('content')

        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h1>Receive SMS Online</h1>
                <p class="left_15">
                    This tool can be useful if you want to protect your privacy by keeping your real phone number to yourself.
                </p>

                <h2>How To Use?</h2>
                <p class="left_15">
                    Here you will find some numbers, just use one with your online registrations, and the inbound messages will show up on this site within seconds.
                </p>                
				
				<h2>You think this might help someone else?</h2>
                
				<center>
								<!-- Sharingbutton Facebook -->
<a class="btn btn-lg share" href="https://facebook.com/sharer/sharer.php?u=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Twitter -->
<a class="btn btn-lg share" href="https://twitter.com/intent/tweet/?text=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;url=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Google+ -->
<a class="btn btn-lg share" href="https://plus.google.com/share?url=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--google resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.37 12.93c-.73-.52-1.4-1.27-1.4-1.5 0-.43.03-.63.98-1.37 1.23-.97 1.9-2.23 1.9-3.57 0-1.22-.36-2.3-1-3.05h.5c.1 0 .2-.04.28-.1l1.36-.98c.16-.12.23-.34.17-.54-.07-.2-.25-.33-.46-.33H7.6c-.66 0-1.34.12-2 .35-2.23.76-3.78 2.66-3.78 4.6 0 2.76 2.13 4.85 5 4.9-.07.23-.1.45-.1.66 0 .43.1.83.33 1.22h-.08c-2.72 0-5.17 1.34-6.1 3.32-.25.52-.37 1.04-.37 1.56 0 .5.13.98.38 1.44.6 1.04 1.84 1.86 3.55 2.28.87.23 1.82.34 2.8.34.88 0 1.7-.1 2.5-.34 2.4-.7 3.97-2.48 3.97-4.54 0-1.97-.63-3.15-2.33-4.35zm-7.7 4.5c0-1.42 1.8-2.68 3.9-2.68h.05c.45 0 .9.07 1.3.2l.42.28c.96.66 1.6 1.1 1.77 1.8.05.16.07.33.07.5 0 1.8-1.33 2.7-3.96 2.7-1.98 0-3.54-1.23-3.54-2.8zM5.54 3.9c.33-.38.75-.58 1.23-.58h.05c1.35.05 2.64 1.55 2.88 3.35.14 1.02-.08 1.97-.6 2.55-.32.37-.74.56-1.23.56h-.03c-1.32-.04-2.63-1.6-2.87-3.4-.13-1 .08-1.92.58-2.5zM23.5 9.5h-3v-3h-2v3h-3v2h3v3h2v-3h3"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Tumblr -->
<a class="btn btn-lg share" href="https://www.tumblr.com/widgets/share/tool?posttype=link&amp;title=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;caption=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;content=https%3A%2F%2Freceive-sms.com%2F&amp;canonicalUrl=https%3A%2F%2Freceive-sms.com%2F&amp;shareSource=tumblr_share_button" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--tumblr resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.5.5v5h5v4h-5V15c0 5 3.5 4.4 6 2.8v4.4c-6.7 3.2-12 0-12-4.2V9.5h-3V6.7c1-.3 2.2-.7 3-1.3.5-.5 1-1.2 1.4-2 .3-.7.6-1.7.7-3h3.8z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Pinterest -->
<a class="btn btn-lg share" href="https://pinterest.com/pin/create/button/?url=https%3A%2F%2Freceive-sms.com%2F&amp;media=https%3A%2F%2Freceive-sms.com%2F&amp;description=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online." target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--pinterest resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5 .02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton LinkedIn -->
<a class="btn btn-lg share" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Freceive-sms.com%2F&amp;title=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;summary=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;source=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Reddit -->
<a class="btn btn-lg share" href="https://reddit.com/submit/?url=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--reddit resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 11.5c0-1.65-1.35-3-3-3-.96 0-1.86.48-2.42 1.24-1.64-1-3.75-1.64-6.07-1.72.08-1.1.4-3.05 1.52-3.7.72-.4 1.73-.24 3 .5C17.2 6.3 18.46 7.5 20 7.5c1.65 0 3-1.35 3-3s-1.35-3-3-3c-1.38 0-2.54.94-2.88 2.22-1.43-.72-2.64-.8-3.6-.25-1.64.94-1.95 3.47-2 4.55-2.33.08-4.45.7-6.1 1.72C4.86 8.98 3.96 8.5 3 8.5c-1.65 0-3 1.35-3 3 0 1.32.84 2.44 2.05 2.84-.03.22-.05.44-.05.66 0 3.86 4.5 7 10 7s10-3.14 10-7c0-.22-.02-.44-.05-.66 1.2-.4 2.05-1.54 2.05-2.84zM2.3 13.37C1.5 13.07 1 12.35 1 11.5c0-1.1.9-2 2-2 .64 0 1.22.32 1.6.82-1.1.85-1.92 1.9-2.3 3.05zm3.7.13c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2zm9.8 4.8c-1.08.63-2.42.96-3.8.96-1.4 0-2.74-.34-3.8-.95-.24-.13-.32-.44-.2-.68.15-.24.46-.32.7-.18 1.83 1.06 4.76 1.06 6.6 0 .23-.13.53-.05.67.2.14.23.06.54-.18.67zm.2-2.8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm5.7-2.13c-.38-1.16-1.2-2.2-2.3-3.05.38-.5.97-.82 1.6-.82 1.1 0 2 .9 2 2 0 .84-.53 1.57-1.3 1.87z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton XING -->
<a class="btn btn-lg share" href="https://www.xing.com/app/user?op=share;url=https%3A%2F%2Freceive-sms.com%2F;title=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online." target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--xing resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10.2 9.7l-3-5.4C7.2 4 7 4 6.8 4h-5c-.3 0-.4 0-.5.2v.5L4 10 .4 16v.5c0 .2.2.3.4.3h5c.3 0 .4 0 .5-.2l4-6.6v-.5zM24 .2l-.5-.2H18s-.2 0-.3.3l-8 14v.4l5.2 9c0 .2 0 .3.3.3h5.4s.3 0 .4-.2c.2-.2.2-.4 0-.5l-5-8.8L24 .7V.2z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton WhatsApp -->
<a class="btn btn-lg share" href="whatsapp://send?text=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.%20https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Hacker News -->
<a class="btn btn-lg share" href="https://news.ycombinator.com/submitlink?u=https%3A%2F%2Freceive-sms.com%2F&amp;t=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online." target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--hackernews resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 140"><path fill-rule="evenodd" d="M60.94 82.314L17 0h20.08l25.85 52.093c.397.927.86 1.888 1.39 2.883.53.994.995 2.02 1.393 3.08.265.4.463.764.596 1.095.13.334.262.63.395.898.662 1.325 1.26 2.618 1.79 3.877.53 1.26.993 2.42 1.39 3.48 1.06-2.254 2.22-4.673 3.48-7.258 1.26-2.585 2.552-5.27 3.877-8.052L103.49 0h18.69L77.84 83.308v53.087h-16.9v-54.08z"></path></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton VK -->
<a class="btn btn-lg share" href="http://vk.com/share.php?title=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;url=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--vk resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.547 7h-3.29a.743.743 0 0 0-.655.392s-1.312 2.416-1.734 3.23C14.734 12.813 14 12.126 14 11.11V7.603A1.104 1.104 0 0 0 12.896 6.5h-2.474a1.982 1.982 0 0 0-1.75.813s1.255-.204 1.255 1.49c0 .42.022 1.626.04 2.64a.73.73 0 0 1-1.272.503 21.54 21.54 0 0 1-2.498-4.543.693.693 0 0 0-.63-.403h-2.99a.508.508 0 0 0-.48.685C3.005 10.175 6.918 18 11.38 18h1.878a.742.742 0 0 0 .742-.742v-1.135a.73.73 0 0 1 1.23-.53l2.247 2.112a1.09 1.09 0 0 0 .746.295h2.953c1.424 0 1.424-.988.647-1.753-.546-.538-2.518-2.617-2.518-2.617a1.02 1.02 0 0 1-.078-1.323c.637-.84 1.68-2.212 2.122-2.8.603-.804 1.697-2.507.197-2.507z"/></svg>
    </div>
  </div>
</a>

<!-- Sharingbutton Telegram -->
<a class="btn btn-lg share" href="https://telegram.me/share/url?text=How%20To%20bypass%20SMS%20verification%20on%20any%20website%20and%20protect%20your%20identity%20online.&amp;url=https%3A%2F%2Freceive-sms.com%2F" target="_blank" aria-label="">
  <div class="resp-sharing-button resp-sharing-button--telegram resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M.707 8.475C.275 8.64 0 9.508 0 9.508s.284.867.718 1.03l5.09 1.897 1.986 6.38a1.102 1.102 0 0 0 1.75.527l2.96-2.41a.405.405 0 0 1 .494-.013l5.34 3.87a1.1 1.1 0 0 0 1.046.135 1.1 1.1 0 0 0 .682-.803l3.91-18.795A1.102 1.102 0 0 0 22.5.075L.706 8.475z"/></svg>
    </div>
  </div>
</a>





				</center>

            </div>
        </div>

        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>Demo Numbers</h2>

                <p class="left_15">
                    Use the demo numbers below to receive text messages, click to check inbound SMS for a specific number.
                </p>
                @yield('numbers_table')


                <p class="left_15">
                    Subscribe to our news letter to get notified when ever we update the free numbers. Privacy is what this website is about. You will never receive spam from us.
                </p>
                <br/>

                {{ Form::open(array('action' => 'SubscribersController@subscribe', 'id' => 'subscribe-form'))}}
                    <div class="container-fluid">
                        <div class="col-lg-2 col-md-1"> </div>
                        <div class="col-lg-8 col-md-10">
                            <div class="input-group text-center">
                                <input
                                        type="email"
                                        class="form-control input-lg"
                                        data-toggle="tooltip"
                                        name="email"
                                        placeholder="[E-MAIL]"
                                        title="Enter your email here"
                                        onfocus="this.select();"
                                        onmouseup="return false;"
										max-width: 200px;
                                        required

                                >

                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-primary btn-lg" value="Subscribe">
                        </span>

                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                <br/>





            </div>
        </div>


        <div class="container width-fix col-sm-12">
            <div class="jumbotron welcome-texture">
                <h2>Inbound Messages - <span class="text-success">Live</span></h2>
                @yield('messages_table')

            </div>
        </div>





@stop



@section('bottom')
    @if(!Auth::check())
        <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <a href="/register">
                <button class="btn btn-lg btn-success">
                    Create Your Private Inbox
                </button>
            </a>
        </div>
        <br/><br/><br/>

    @endif
@stop