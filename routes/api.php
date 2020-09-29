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
Route::get('/',function()
{
    return response()->json("Welcome to laravel api");
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('login','AuthController@login');
    Route::post('register','AuthController@register');

    Route::group(['middleware'=>'auth:api'], function () {
        Route::get('logout','AuthController@logout');
        Route::get('user','AuthController@user');

    });
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::middleware('can:isAdmin')->group(function ()
{
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'product'
], function () {
Route::post('store','API\ProductController@store');
Route::get('contact','API\ContactController@index');
Route::delete('delete/{id}','API\ProductController@delete');
});
});
Route::get('home','API\ProductController@index');

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'contact'
], function () {
Route::get('nearby','API\NearbyController@index');
Route::post('store','API\ContactController@store');
});

