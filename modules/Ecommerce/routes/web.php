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

use Modules\Ecommerce\app\Http\Controllers\EcommerceController;

Route::prefix('ecommerce')->group(function() {
    Route::get('/', [EcommerceController::class, 'index']);
});
