<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([

    'middleware' => 'api',
    'prefix'=>'v1',

], function () {

    Route::post('/login', 'AuthController@login');
    Route::post('/register','AuthController@register');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user-profile','AuthController@profile');

    /**** DEPOSIT *****/
    Route::post('/deposit', 'DepositController@depositFunds')->name('deposit');
    Route::get('/payment/gen_callback', 'DepositController@paystackCallbackURL')->name('paystackCallback');

 /**** TRANSFER *****/
    Route::post('/transfer','TransactionController@transferFunds');
});


