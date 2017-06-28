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
Route::get('mailtest', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));

    $email = "abdelilah.sbaai@gmail.com";
    $name = "Abdelilah";
    $date = Carbon::now();
    $amount = "10";
    $finalBalance = "60";
    $type = "PayPal";

    return $markdown->render('emails.topupReceipt');
});

Route::pattern('number', '[0-9]{8,13}');

Auth::routes();

Route::get('/', 'pagesController@home');

Route::post('/troppo','messagesController@troppo');
Route::get('/troppo','messagesController@troppo');
Route::get('/contact','pagesController@contact');
Route::get('/support', 'userController@support');

Route::post('contact','contactController@send');
Route::post('support', 'contactController@send');

Route::get('/test','PaymentController@test');
Route::get('/hash/{days}','pagesController@test');

Route::get('/tester','pagesController@tester');
Route::get('/faqs','pagesController@faqs');
Route::get('/pricing','pagesController@pricing');

Route::get('/topup','userController@topup');
Route::post('/topup','PaymentController@RedirectToPayment');

Route::get('/home','pagesController@home');

Route::post('renew','userController@renewNumbers');


Route::get('/delete/{number}','userController@deleteNumber');

//Route::get('/ipn/{type}','PaymentController@IPN');
Route::post('/ipn/{type}','PaymentController@IPN');

Route::post('/ipn/payza','PaymentController@payzaIPN');

Route::get('/admin', 'pagesController@admin');

Route::get('/messages','messagesController@getPublic');

Route::get('/newmessages/{id}/{num}','messagesController@newMessages');

Route::get('/coupon/{amount}/{type}/{code}','PaymentController@applyCoupon');
Route::get('/price/{amount}/{period}','PaymentController@showPrice');

Route::post('subscribe','SubscribersController@subscribe');

Route::get('/inbox', 'userController@inbox');
Route::get('/numbers', 'userController@numbers');

Route::get('/account', 'userController@account');
Route::post('/account', 'userController@updateInfo');

Route::get('/api', 'pagesController@api');

Route::post('/add', 'userController@addNumbers');
Route::post('/delete', 'userController@doDeleteNumber');

Route::get('/{number}', 'pagesController@showMessages');
Route::get('/inbox/{number}', 'userController@inbox');

Route::get('/api/{email}/{password}/{number}', 'apiController@show');
Route::get('/api/{email}/{password}', 'apiController@show');

Route::get('/log', 'messagesController@logMessage');
Route::post('/log', 'messagesController@logMessage');

Route::get('/success', function () {
    return view('message')->with('content', 'Thanks for topping up your account. Your receipt has been emailed to you.')->with('titleClass', 'text-success')->with('title', 'Thank you!');
});

Route::get('/fail', function () {
    return view('message')->with('content', 'Your payment didn\'t complete successfully. Please try again later.')->with('titleClass', 'text-danger')->with('title', 'Payment Failed!');
});