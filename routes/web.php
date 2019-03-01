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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/shareinfo', 'ShareInfoController@import')->name('import');
Route::get('/shareindex', 'ShareInfoController@indexInfo')->name('indexInfo');
Route::get('/checkfno', 'ShareInfoController@isFno')->name('checkfno');
Route::get('/checkoi', 'ShareInfoController@oiDetail')->name('oiDetail');
Route::get('/sharedatapull', 'StockDataController@shareData')->name('shareData');
