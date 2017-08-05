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
    return $markdown->render('emails.numbersReady');
});

Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'userController@confirm'
]);

Route::get('unsubscribe/{email}', [
    'as' => 'unsubscribe_path',
    'uses' => 'SubscribersController@unsubscribe'
]);



Route::get('subscribe/verify/{email}', [
    'as' => 'subscription',
    'uses' => 'SubscribersController@confirm'
]);

Route::get('/test','PaymentController@test');

Route::pattern('number', '[0-9]{8,13}');

Auth::routes();

Route::get('/admin', 'adminController@dashboard');
Route::get('/admin/dashboard', 'adminController@dashboard');
Route::get('/admin/mailer', 'adminController@mailer');
Route::get('/admin/support', 'adminController@support');

Route::get('/admin/numbers', 'adminController@showNumbers');
Route::get('/admin/topups', 'adminController@showTopups');
Route::get('/admin/orders', 'adminController@showOrders');
Route::get('/admin/sources', 'adminController@showSources');
Route::post('/admin/sources', 'adminController@showSources');


Route::get('/admin/give', 'adminController@give');
Route::post('/admin/give', 'adminController@giveNumbers');

Route::get('/admin/blacklists', 'adminController@blacklists');

Route::get('/admin/coupon', 'adminController@coupon');
Route::post('/admin/coupon', 'adminController@addCoupon');

Route::get('/admin/mailer/preview/{text1}/{text2}/{heading1}/{heading2}/{button}/{buttonURL}', 'adminController@preview');
Route::post('/admin/mailer', 'adminController@send');

Route::get('/admin/test', 'adminController@test');

Route::get('/admin/chart/income', 'adminController@incomeChart');
Route::get('/admin/chart/subscribers', 'adminController@subscribersChart');
Route::get('/admin/chart/topups', 'adminController@topupsChart');
Route::get('/admin/chart/unsubscribers', 'adminController@unsubscribersChart');
Route::get('/admin/chart/registration', 'adminController@registrationChart');
Route::get('/admin/chart/chargebacks', 'adminController@chargebacksChart');
Route::get('/admin/chart/coupon', 'adminController@couponChart');


Route::get('/', 'pagesController@home');
Route::get('/home', 'pagesController@home');
Route::post('/troppo','messagesController@troppo');
Route::get('/troppo','messagesController@troppo');
Route::get('/contact','pagesController@contact');
Route::get('/support', 'userController@support');

Route::post('contact','contactController@send');
Route::post('support', 'supportController@send');


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