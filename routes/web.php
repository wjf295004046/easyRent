<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'IndexController@index');

// Authentication Routes...
//Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');
//Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
//Route::post('password/phone', 'Auth\ForgotPasswordController@resetPassword');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Auth::routes();
Route::post('password/phone', 'Auth\ForgotPasswordController@reset');

Route::get('/home', 'HomeController@index');

Route::post('/quickLogin', 'Auth\QuickLoginController@quickLogin');
Route::get('/common/getverify', 'CommonController@getVerify');
Route::post('/common/getverify','CommonController@getVerify');
Route::post('/common/userexists', 'CommonController@userExists');
Route::post('/common/checkrent', 'CommonController@checkRentInfo');
Route::post('/common/saveaddress', 'CommonController@saveAddress');
Route::get('/common/cert', 'CommonController@cert')->middleware('auth');
Route::post('/common/certsave', 'CommonController@certSave');

Route::get("/show/{opt}", 'HouseController@showSearchHouse');
//Route::get("/house/{id}", 'HouseController@showHouse');

Route::resource("orders", 'OrderController');
Route::resource("fangdong", 'LandlordController');
Route::resource("house", 'HouseController');
Route::get("/house/create", 'HouseController@create')->middleware('cert');