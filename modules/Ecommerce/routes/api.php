<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/ecommerce', function (Request $request) {
    return $request->user();
});

Route::get('product/{slug}+p{id}', [ProductController::class,'show'])->name('api.product.show');
Route::get('product/{slug}+v{id}', [ProductController::class,'showVariation'])->name('api.product.showVariation');
