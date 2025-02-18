<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/admin/login', 'App\Http\Controllers\Admin\AuthController@authenticate');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
