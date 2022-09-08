<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/catgeory', [CategoryController::class, 'getCategories']);
Route::get('/catgeory/{id}', [CategoryController::class, 'getCategory']);
Route::get('/catgeory/delete/{id}', [CategoryController::class, 'getCategoryDelete']);
Route::post('/catgeory/add', [CategoryController::class, 'postAddCategory']);
Route::post('/catgeory/edit/{id}', [CategoryController::class, 'postEditCategory']);
Route::get('/catgeory/{id}/products', [CategoryController::class, 'getProductsWithCategory']);


Route::post('/product/{id}', [ProductController::class, 'update']);
Route::resource('product', ProductController::class);
