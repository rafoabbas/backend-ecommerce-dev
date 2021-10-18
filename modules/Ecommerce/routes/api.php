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

Route::group(['prefix' => 'product','as' => 'ecommerce.product.'], function (){
    Route::get('{slug}+p{product}', [ProductController::class,'show'])->name('show');
    Route::get('{slug}+v{productVariation}', [ProductController::class,'showProductVariation'])->name('show.variation');
});
