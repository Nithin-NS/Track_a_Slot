<?php

use Illuminate\Support\Facades\Route;

use App\Mail\WelcomeMail;

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
    return view('index');
})->name('index');

Route::get('/email', function () {
    return new WelcomeMail();
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/saveDetails','App\Http\Controllers\UserDetailsController@saveDetails');
