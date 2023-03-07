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
    return view('welcome');
});

Route::middleware('guest')->namespace('\App\Http\Controllers')->group(function () {

    /**
     * Авторизация
     */
    Route::get('/login', function () {
        return view('auth.login');
    });

    Route::post('/login', 'AuthController@login');

    /**
     * Регистрация
     */
    Route::get('/register', function () {
        return view('auth.register');
    });

    Route::post('/register', 'AuthController@register');
});


Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard.index');
    });
});
