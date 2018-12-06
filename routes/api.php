<?php

use Illuminate\Http\Request;

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
Route::namespace('Api')->group(function () {
    \Illuminate\Support\Facades\Route::apiResource('products', 'ProductController', ['store' => false]);
    Route::get('/teste/{id}', 'ProductSheetController@show');

    Route::post('/products', 'ProductSheetController@store');
    Route::get('/products/{id}', 'ProductController@show');
    Route::put('/products/{id}', 'ProductController@update');
    Route::delete('/products/{id}', 'ProductController@destroy');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
