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
    $redis = \Illuminate\Support\Facades\Redis::connection();

    try {
        $redis->ping();
    }catch (Exception $exception){
        dd($exception->getMessage());
    }
    phpinfo();
//    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::any('/test', [App\Http\Controllers\TestController::class, 'index'])->name('home');
