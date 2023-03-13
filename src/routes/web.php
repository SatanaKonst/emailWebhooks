<?php

use App\Models\Jobs;
use Illuminate\Support\Facades\Auth;
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

Route::namespace('\App\Http\Controllers')->group(function () {

    /**
     * Авторизация
     */
    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('auth.login');
    });

    Route::post('/login', 'AuthController@login');

    /**
     * Регистрация
     */
    Route::get('/register', function () {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('auth.register');
    });

    Route::post('/register', 'AuthController@register');
});


Route::middleware('auth')->group(function () {

    /**
     * Главная страница панели
     */
    Route::get('/dashboard', function () {
        /**
         * Получим все jobs данного пользователя
         */
        $jobs = Jobs::query()->where('user_id', '=', Auth::id())->get();
        return view(
            'dashboard.index',
            array(
                'jobs' => $jobs
            )
        );
    });

    /**
     * Добавить новую задачу
     */
    Route::post('/dashboard/addJob', '\App\Http\Controllers\WebhookEmail@addJob');

    /**
     * Добавить новое правило
     */
    Route::post('/dashboard/addJobRule', '\App\Http\Controllers\WebhookEmail@addRule');

    /**
     * Детальная страница задачи
     */
    Route::get(
        '/dashboard/{job}',
        function (Jobs $job) {

            $viewParam = array(
                'job' => $job,
            );

            if (($job->user_id !== Auth::id())) {
                $viewParam['error'] = array(
                    'Задача не найдена'
                );
            }
            return view(
                'dashboard.job-detail',
                $viewParam
            );
        }
    );

    Route::get('/imap', function () {
        $imap = new \App\Http\Controllers\Imap();
        $messages = $imap->get()->filterEmailByFrom('satana.konst@gmail.com');
        dump($messages);
    });


});
