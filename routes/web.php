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
Route::get('/bhavcopy', 'StockDataController@bhavCopyDataPull')->name('bhavcopy');
Route::get('/participantOIData', 'ParticipantController@participantOIData')->name('participantOIData');
Route::get('/option', 'OptionController@stockOptionChain')->name('option');
Route::get('/indexoption', 'OptionController@indexOptionChain')->name('indexoption');
Route::get('/openinterest', 'OptionController@openInterestBrkOut')->name('optionBrkOut');
Route::get('/stockopeninterest', 'OptionController@stockOptionChain')->name('stockopeninterest');
Route::get('/avgoi', 'OpenInterestController@avgOIPerDay')->name('avgoi');
Route::get('/oispurts', 'OpenInterestController@oiSpurts')->name('oispurts');
Route::get('/probability', 'ProbabilityController@viewProb')->name('viewProb');
Route::get('/lekhajokha', 'DashboardController@landingPage')->name('landingPage');
Route::get('/participant', 'ParticipantController@perSegment')->name('perSegment');
Route::get('/raw', 'OptionController@jabraAction')->name('jabraAction');
Route::get('/jabraiv', 'OptionController@jabraIV')->name('jabraIV');
Route::get('/niftyexpirywise/{expiry}', 'OptionController@niftyExpiryWise')->name('jabraIV');
Route::get('/jabraopeninterest', 'DashboardController@strongOI')->name('strongOI');
Route::get('/fiboo', 'FibonacciController@fibonacciCalculator')->name('fibb');
Route::get('/fii', 'FibonacciController@fiiData')->name('fii');
