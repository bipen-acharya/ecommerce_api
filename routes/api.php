<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
});
Route::get('/catgeory', [CategoryController::class, 'getCategories']);
Route::get('/catgeory/{id}', [CategoryController::class, 'getCategory']);
Route::get('/catgeory/delete/{id}', [CategoryController::class, 'getCategoryDelete']);
Route::post('/catgeory/add', [CategoryController::class, 'postAddCategory']);
Route::post('/catgeory/edit/{id}', [CategoryController::class, 'postEditCategory']);
Route::get('/catgeory/{id}/products', [CategoryController::class, 'getProductsWithCategory']);


Route::post('/product/{id}', [ProductController::class, 'update']);
Route::resource('product', ProductController::class);


Route::post('/cart/add', [CartController::class, 'postAddCart']);
Route::get('/cart', [CartController::class, 'getCarts']);
Route::get('/cart/delete/{id}', [CartController::class, 'getCartDelete']);

Route::get('/user', [UserController::class, 'getUser']);


Route::get('/user/{id}/carts', [CartController::class, 'getUserWithCarts']);


Route::get('/order', [OrderController::class, 'getOrder']);
Route::post('/order/add', [OrderController::class, 'addOrder']);
