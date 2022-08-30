<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RoutesubserviceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');
Route::get('auth/google', 'HomeController@redirectToGoogle')->name('redirectToGoogle');
Route::get('auth/google/callback', 'HomeController@handleGoogleCallback')->name('handleGoogleCallback');

// Customer
Route::post('customer/register', 'HomeController@register')->name('customerregister');
Route::post('customer/login/submit', 'HomeController@loginsubmit')->name('customerloginsubmit');
Route::post('/otp/submit', 'HomeController@otpsubmit')->name('otpsubmit');
Route::get('customer/register', 'HomeController@customerdashboard')->name('customerdashboard');
Route::get('customer/logout', 'HomeController@customerlogout')->name('customerlogout');

Route::group(['middleware' => ['customer'], 'prefix' => 'customer'], function () {

   Route::get('/profile', 'HomeController@customerprofile')->name('customerprofile');

   Route::get('/chat/', 'HomeController@chat')->name('customerchat');
   Route::get('/chatdetail/{id}', 'HomeController@chatdetail')->name('customerchatdetail');

   //post
   Route::post('/chatsave', 'HomeController@chatsave')->name('customerchatsave');
   Route::post('/chatsave/media', 'HomeController@chatsavemedia')->name('customerchatsavemedia');
   Route::post('/chatsave/voice', 'HomeController@chatsavevoice')->name('customerchatsavevoice');

   Route::post('/chatget', 'HomeController@chatget')->name('customerchatget');

   Route::post('/profile/submit', 'HomeController@customerprofilesubmit')->name('customerprofilesubmit');
});
