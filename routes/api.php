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
Route::prefix('customer')->group(function () {

    Route::post('login', 'AuthController@login');
    Route::post('register','AuthController@register');
   //mero controller bata hunxa
    Route::group([
        'middleware' => 'auth:api',
        'prefix' => 'product'
    ], function () {
    Route::post('store','API\ProductController@store');
    });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('product/{options}', 'API\ProductController@index');
        Route::post('product/details/{id}', 'API\ProductController@details');
        Route::post('product/add', 'API\ProductController@store');
        Route::get('product/order', 'API\ProductController@order');
        Route::get('product/my_order', 'API\ProductController@my_order');
        Route::post('product/place_order', 'API\ProductController@place_order');
        Route::delete('product/delete', 'API\ProductController@destroy');
    });
});


// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'password'
// ], function () {
//     Route::post('create', 'PasswordResetController@create');
//     Route::get('find/{token}', 'PasswordResetController@find');
//     Route::post('reset', 'PasswordResetController@reset');
// });


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'product'
], function () {
Route::post('store','API\ProductController@store');
});
// Route::get('home','API\ProductController@index');

// Route::group([
//     'middleware' => 'auth:api',
//     'prefix' => 'contact'
// ], function () {
// Route::get('nearby','API\NearbyController@index');
// Route::post('store','API\ContactController@store');
// });

