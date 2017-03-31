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
Route::get('/home/modifypwd', 'HomeController@modifyPwd');
Route::get('/home/order', 'HomeController@orderManage');
Route::get('/home/comment', 'HomeController@commentManage');
Route::get('/home/liver', 'HomeController@liverManage');
Route::post('/home/savecomment', 'HomeController@saveComment');
Route::post('/home/showcomment', 'HomeController@showComment');
Route::post('/home/deleteliver', 'HomeController@deleteLiver');
Route::post('/home/saveeditliver', 'HomeController@saveEditLiver');
Route::post('/home/saveliver', 'HomeController@saveLiver');

Route::post('/quickLogin', 'Auth\QuickLoginController@quickLogin');
Route::get('/common/getverify', 'CommonController@getVerify');
Route::post('/common/getverify','CommonController@getVerify');
Route::post('/common/userexists', 'CommonController@userExists');
Route::post('/common/checkrent', 'CommonController@checkRentInfo');
Route::post('/common/saveaddress', 'CommonController@saveAddress');
Route::get('/common/cert', 'CommonController@cert')->middleware('auth');
Route::post('/common/certsave', 'CommonController@certSave');
Route::post('/common/changephoto', 'CommonController@changePhoto');

Route::get("/show/{opt}", 'HouseController@showSearchHouse');
Route::get('fangdong', 'LandlordController@index');
Route::get("fangdong/order", 'LandlordController@orderManage');
Route::get("fangdong/comment", 'LandlordController@commentManage');
Route::get("fangdong/house", 'LandlordController@houseList');
Route::get("fangdong/userinfo", 'LandlordController@userInfo');
Route::get("fangdong/modifypwd", 'LandlordController@modifyPwd');
Route::get('fangdong/address', 'LandlordController@addressManage');

Route::post("fangdong/modifypwd", 'LandlordController@doModifyPwd');
Route::post("fangdong/confirmorder", 'LandlordController@confirmOrder');
Route::post("fangdong/cancelorder", 'LandlordController@cancelOrder');
Route::post("fangdong/checkin", 'LandlordController@checkIn');
Route::post("fangdong/finishorder", 'LandlordController@finishOrder');
Route::post("fangdong/showcomment", 'LandlordController@showComment');
Route::post('fangdong/replycomment', 'LandlordController@replyComment');
Route::post('fangdong/ajaxgetorder', 'LandlordController@ajaxGetOrder');
Route::post('fangdong/ajaxgetcomment', 'LandlordController@ajaxGetComment');
Route::post("fangdong/gethouse", 'LandlordController@getHouseInfo');
Route::post("fangdong/showedithouse", 'LandlordController@showEditHouse');
Route::post("fangdong/edithouse", 'LandlordController@editHouse');
Route::post("fangdong/edituserinfo", 'LandlordController@editUserInfo');
Route::post("fangdong/edituser", 'LandlordController@editUser');
Route::post("fangdong/addaddress", 'LandlordController@addAddress');
Route::post("fangdong/deleteaddress", 'LandlordController@deleteAddress');



//Route::get("/house/{id}", 'HouseController@showHouse');

Route::resource("orders", 'OrderController');
//Route::resource("fangdong", 'LandlordController');
Route::resource("house", 'HouseController');
Route::get("/house/create", 'HouseController@create')->middleware('cert');