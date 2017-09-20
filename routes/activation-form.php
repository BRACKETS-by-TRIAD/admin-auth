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

Route::middleware(['web'])->group(function () {
    Route::namespace('Brackets\AdminAuth\Http\Controllers\Auth')->group(function () {
        Route::get('/admin/activation',                 'ActivationEmailController@showLinkRequestForm');
        Route::post('/admin/activation/send',           'ActivationEmailController@sendActivationEmail');
    });
});