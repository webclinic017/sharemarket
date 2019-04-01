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
Route::get('/datToJson', 'ShareInfoController@datToJson')->name('datToJson');
Route::get('/delivery', 'StockDataController@delivery')->name('delivery');
Route::get('/participantOIData', 'ParticipantController@participantOIData')->name('participantOIData');
Route::get('/bhavcopy', 'StockDataController@bhavCopyDataPull')->name('bhavcopy');
Route::get('/option', 'OptionController@optionDataFetch')->name('option');
Route::get('/openinterest', 'OptionController@openInterestBrkOut')->name('optionBrkOut');
