<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Mail\Markdown;
use Carbon\Carbon;

use Illuminate\Support\Facades\Input;

Route::get('mailtest', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.numbersReady');
});

Route::get('/ppdisposable','PaymentController@ppdisposable');

Route::get('/delete','userController@deleteAccount');

Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'userController@confirm'
]);

Route::get('/fast/support/{id}', 'adminController@fastSupport');

Route::get('/fast/support/{?id}', 'adminController@sendResponse');

Route::post('unsubscribe','SubscribersController@unsubscribe');

Route::get('unsubscribe/{email}','SubscribersController@showUnsubscribe');
Route::get('unsubscribe','SubscribersController@showUnsubscribe');

Route::get('subscribe/verify/{email}', [
    'as' => 'subscription',
    'uses' => 'SubscribersController@confirm'
]);

Route::get('/test','PaymentController@test');

Route::get('/datafix','adminController@dataFix');

Route::pattern('number', '[0-9]{8,13}');

Auth::routes();

Route::get('/getdisposable', 'PaymentController@getdisposable');
Route::get('/pp', 'PaymentController@pp');
Route::get('/ppsned', 'PaymentController@ppsend');

Route::get('/admin', 'adminController@dashboard');
Route::get('/admin/dashboard', 'adminController@dashboard');
Route::get('/admin/mailer', 'adminController@mailer');
Route::get('/admin/flatmailer', 'adminController@flatMailer');
Route::get('/admin/support', 'adminController@support');

Route::get('/admin/contact', 'adminController@contact');

Route::get('/admin/numbers', 'adminController@showNumbers');
Route::get('/admin/topups', 'adminController@showTopups');
Route::get('/admin/orders', 'adminController@showOrders');
Route::get('/admin/sources', 'adminController@showSources');
Route::post('/admin/sources', 'adminController@showSources');

Route::post('/admin/support', 'adminController@sendResponse');
Route::post('/admin/contact', 'adminController@sendResponse');
Route::get('/admin/support/delete/{id}', 'adminController@deleteEmail');

Route::get('/admin/give', 'adminController@give');
Route::post('/admin/give', 'adminController@giveNumbers');

Route::get('/admin/blacklists', 'adminController@blacklists');

Route::get('/admin/coupon', 'adminController@coupon');
Route::post('/admin/coupon', 'adminController@addCoupon');

Route::get('/admin/mailer/preview/{text1}/{text2}/{text3}/{text4}/{heading1}/{heading2}/{heading3}/{heading4}/{img1}/{img2}/{button1}/{button2}/{button3}/{buttonURL1}/{buttonURL2}/{buttonURL}', 'adminController@preview');

Route::get('/admin/sendtest/{text1}/{text2}/{text3}/{text4}/{heading1}/{heading2}/{heading3}/{heading4}/{img1}/{img2}/{button1}/{button2}/{button3}/{buttonURL1}/{buttonURL2}/{buttonURL}/{subject}', 'adminController@sendtest');

Route::post('/admin/mailer', 'MaillingController@makeList')
;Route::post('/admin/mailer', 'MaillingController@makeFlatList');



Route::get('/admin/chart/income', 'adminController@incomeChart');
Route::get('/admin/chart/subscribers', 'adminController@subscribersChart');
Route::get('/admin/chart/topups', 'adminController@topupsChart');
Route::get('/admin/chart/unsubscribers', 'adminController@unsubscribersChart');
Route::get('/admin/chart/registration', 'adminController@registrationChart');
Route::get('/admin/chart/chargebacks', 'adminController@chargebacksChart');
Route::get('/admin/chart/coupon', 'adminController@couponChart');


Route::get('/', function(){
    if (Input::get('tag')){
        $tag = urlencode(Input::get('tag'));
        if (strpos($tag, "%") !== false) {
            $ret = new \App\Http\Controllers\pagesController();
            return $ret->home();
        }else{
            return Redirect::to('/sms/' . $tag, 301);
        }

    }else{
        $ret = new \App\Http\Controllers\pagesController();
        return $ret->home();
    }
});

Route::get('/home', 'pagesController@home');

Route::get('/subscribed', 'pagesController@home');

Route::get('/contact','pagesController@contact');
Route::get('/support', 'userController@support');

Route::post('contact','contactController@store');
Route::post('support', 'supportController@send');




Route::get('/faqs','pagesController@faqs');
Route::get('/pricing','pagesController@pricing');


Route::post('/admin','PaymentController@RedirectToPaymentInternal');

Route::get('/topup','userController@topup');
Route::post('/topup','PaymentController@RedirectToPayment');

Route::get('/home','pagesController@home');

Route::post('renew','userController@renewNumbers');


Route::get('/delete/{number}','userController@deleteNumber');


Route::post('/ipn/payza','PaymentController@payzaIPN');

Route::post('/ipn/paypal','PaymentController@paypalIPN');
Route::post('/ipn/smsver','PaymentController@paypalIPN');
Route::post('/ipn/payeer','PaymentController@payeerIPN');

//Route::get('/messages','messagesController@getPublic');
Route::get('/lm','messagesController@lastMessage');

Route::get('/newmessages/{id}/{num}','messagesController@newMessages');

Route::get('/coupon/{amount}/{type}/{code}','PaymentController@applyCoupon');
Route::get('/price/{amount}/{period}','PaymentController@showPrice');

Route::post('subscribe','SubscribersController@subscribe');

Route::get('/inbox', 'userController@inbox');

Route::get('/sendlist', 'MaillingController@SendList');

Route::get('/resend', 'userController@resendConfirmation');

Route::get('/inbox/welcome', 'userController@inbox');
Route::get('/numbers', 'userController@numbers');

Route::get('/account', 'userController@account');
Route::post('/account', 'userController@updateInfo');

Route::get('/api', 'pagesController@api');

Route::post('/add', 'userController@addNumbers');
Route::post('/delete', 'userController@doDeleteNumber');

Route::get('/{number}', 'pagesController@showMessages');

Route::get('/inbox/{number}', 'userController@inbox');

Route::get('/inbox/b/{code}', 'userController@inbox');

Route::get('/api/{email}/{password}/{number}', 'apiController@show');
Route::get('/api/{email}/{password}', 'apiController@show');

Route::post('/log/tropo','messagesController@tropo');
Route::get('/log/tropo','messagesController@tropo');

Route::get('/log/generic/{from}/{to}/{text}','messagesController@genericLogSMS');


Route::post('/log/bandwidth','messagesController@bandwidth');
Route::get('/log/bandwidth','messagesController@bandwidth');

Route::get('/log/system', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/success', function () {
    return view('message')->with('content', 'Thanks for topping up your account. Your receipt will be emailed to you.')->with('titleClass', 'text-success')->with('title', 'Thank you!');
});

Route::get('/renew/success', function () {
    return view('message')->with('content', 'Your renewal was successful.')->with('titleClass', 'text-success')->with('title', 'Thank you!');
});

Route::get('/add/success', function () {
    return view('message')->with('content', 'Your number(s) has been successfully added to your account.')->with('titleClass', 'text-success')->with('title', 'Thank you!');
});



Route::get('/fail', function () {
    return view('message')->with('content', 'Your payment didn\'t complete successfully. Please try again later.')->with('titleClass', 'text-danger')->with('title', 'Payment Failed!');
});

Route::get('/sms/{tag?}', 'pagesController@showTag')->where('tag', '(.*)');

// redirections

Route::get('/receive-sms.com', function(){
    return Redirect::to('/', 301);
});

Route::get('/index.php', function(){
    return Redirect::to('/', 301);
});

Route::get('/us_reach_list.php', function(){
    return Redirect::to('/', 301);
});

Route::get('/contact.php', function(){
    return Redirect::to('/contact', 301);
});

Route::get('/forgot.php', function(){
    return Redirect::to('/password/reset', 301);
});

Route::get('/faq.php', function(){
    return Redirect::to('/faqs', 301);
});

Route::get('/inbox.php', function(){
    return Redirect::to('/login', 301);
});


Route::get('/messages.php', function(){
    if (Input::get('number')){
        $number = Input::get('number');
        return Redirect::to('/' . $number, 301);

    }else{
        if (Input::get('tag')){
            $tag = Input::get('tag');
            if (strpos($tag, "%") !== false) {
                $ret = new \App\Http\Controllers\pagesController();
                return $ret->home();
            }else{
                return Redirect::to('/sms/' . $tag, 301);
            }

        }else{
            return Redirect::to('/', 301);
        }

    }
});





