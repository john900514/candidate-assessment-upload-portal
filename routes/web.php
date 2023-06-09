<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/portal');
});

Route::get('/portal/registration',  'App\Http\Controllers\Users\UserRegistrationController@index');
Route::post('/portal/registration',  'App\Http\Controllers\Users\UserRegistrationController@finish_registration');
