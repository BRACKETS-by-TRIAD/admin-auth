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


/* Auto-generated admin routes */
Route::get('/admin/admin-user',                             'Admin\AdminUsersController@index');
Route::get('/admin/admin-user/create',                      'Admin\AdminUsersController@create');
Route::post('/admin/admin-user/store',                      'Admin\AdminUsersController@store');
Route::get('/admin/admin-user/edit/{admin-user}',           'Admin\AdminUsersController@edit')->name('admin/admin-user/edit');
Route::post('/admin/admin-user/update/{admin-user}',        'Admin\AdminUsersController@update')->name('admin/admin-user/update');
Route::delete('/admin/admin-user/destroy/{admin-user}',     'Admin\AdminUsersController@destroy')->name('admin/admin-user/destroy');