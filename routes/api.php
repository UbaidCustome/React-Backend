<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/admin/login', 'App\Http\Controllers\Admin\AuthController@authenticate');
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('/admin/categories', 'App\Http\Controllers\Admin\CategoryController');
    Route::resource('/admin/products', 'App\Http\Controllers\ProductController');
    Route::resource('/admin/orders', 'App\Http\Controllers\OrderController');
    Route::resource('/users', 'App\Http\Controllers\UserController');
    Route::resource('/admin/brands', 'App\Http\Controllers\Admin\BrandController');
    Route::post('/admin/logout', 'App\Http\Controllers\Admin\AuthController@logout');

    Route::get('/user', 'App\Http\Controllers\Admin\AuthController@user');
    // Route::post('/admin/logout', 'App\Http\Controllers\Admin\AuthController@logout');
    // Route::get('/admin/categories', 'App\Http\Controllers\Admin\CategoryController@index');
    // Route::post('/admin/categories', 'App\Http\Controllers\Admin\CategoryController@store');
    // Route::get('/admin/categories/{id}', 'App\Http\Controllers\Admin\CategoryController@show');
    // Route::put('/admin/categories/{id}', 'App\Http\Controllers\Admin\CategoryController@update');
    // Route::delete('/admin/categories/{id}', 'App\Http\Controllers\Admin\CategoryController@destroy');
    // Route::get('/admin/products', 'App\Http\Controllers\Admin\ProductController@index');
    // Route::post('/admin/products', 'App\Http\Controllers\Admin\ProductController@store');
    // Route::get('/admin/products/{id}', 'App\Http\Controllers\Admin\ProductController@show');
    // Route::put('/admin/products/{id}', 'App\Http\Controllers\Admin\ProductController@update');
    // Route::delete('/admin/products/{id}', 'App\Http\Controllers\Admin\ProductController@destroy');
    // Route::get('/admin/orders', 'App\Http\Controllers\Admin\OrderController@index');
    // Route::get('/admin/orders/{id}', 'App\Http\Controllers\Admin\OrderController@show');
    // Route::put('/admin/orders/{id}', 'App\Http\Controllers\Admin\OrderController@update');
    // Route::delete('/admin/orders/{id}', 'App\Http\Controllers\Admin\OrderController@destroy');
    // Route::get('/admin/users', 'App\Http\Controllers\Admin\UserController@index');
    // Route::get('/admin/users/{id}', 'App\Http\Controllers\Admin\UserController@show');
    // Route::put('/admin/users/{id}', 'App\Http\Controllers\Admin\UserController@update');
    // Route::delete('/admin/users/{id}', 'App\Http\Controllers\Admin\UserController@destroy');
});