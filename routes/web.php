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

Route::redirect('/','/user/login');
Route::group(['namespace'=>'Auth'], function () {
    Route::get('/user/login', 'AuthController@loginForm')->name('login');
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');
});

Route::group(['namespace'=>'Backend','prefix'=>'backend','middleware'=>'auth'], function () {
    Route::get('/dashboard', 'IndexController@index')->name('dashboard');
    Route::resource('/user','UserController',['except'=>'show'])->middleware('is_admin');
    Route::resource('/domain','DomainController',['except'=>'show'])->middleware('is_admin');
    Route::put('/domain/status/{domain}','DomainController@changeStatus')->middleware('is_admin');
    Route::resource('/ad','AdController',['except'=>'show']);
    Route::resource('/promotion','PromotionController',['except'=>'show'])->middleware('is_admin');
    Route::get('/show_promotion_url', 'PromotionController@showUrl')->middleware('is_admin');
    Route::get('/promotion/pages/{promotion_id}', 'PromotionController@pageIndex')->middleware('is_admin');
    Route::get('/promotion/page/create/{promotion_id}', 'PromotionController@pageCreate')->middleware('is_admin');
    Route::post('/promotion/page/create', 'PromotionController@pageStore')->middleware('is_admin');
    Route::get('/promotion/page/edit/{id}', 'PromotionController@pageEdit')->middleware('is_admin');
    Route::post('/promotion/page/update/{id}', 'PromotionController@pageUpdate')->middleware('is_admin');
    Route::get('/promotion/page/get_img/{id}', 'PromotionController@getImgs')->middleware('is_admin');
    Route::get('/promotion/page/delete/{id}', 'PromotionController@deletePage')->middleware('is_admin');
});
Route::group(['namespace'=>'Common','prefix'=>'common','middleware'=>'auth'], function () {
    Route::post('/upload_img', 'UploadController@uploadImg')->middleware('is_admin');
    Route::post('/upload_music', 'UploadController@uploadMusic')->middleware('is_admin');
    Route::post('/upload_images', 'UploadController@uploadImages')->middleware('is_admin');
});