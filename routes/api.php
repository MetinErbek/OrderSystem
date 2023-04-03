<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserFavoritesController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('register', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);


Route::middleware(['auth:api'])->group(function () {
    
    Route::get('/', function(){
        return jsonResponse(TRUE,  'You are Login !');
    });

    Route::get('profile', [UserController::class, 'getUserDetail']);

    Route::post('addtobasket', [OrderController::class, 'addToBasket']);
    Route::post('removefrombasket', [OrderController::class, 'removeFromBasket']);
    Route::get('getbasket', [OrderController::class, 'getBasket']);
    Route::get('makebaskettoorder', [OrderController::class, 'makeBasketToOrder']);
    Route::get('products', [ProductController::class, 'getShowcaseProducts']);
    Route::get('favorites', [UserFavoritesController::class, 'index']);
    Route::post('addfavorite', [UserFavoritesController::class, 'store']);
    Route::post('removefromfavorite', [UserFavoritesController::class, 'removeFromFavorites']);

    
});

Route::prefix('admin')->middleware(['AdminAuth'])->group(function(){
    Route::get('/', function(){
        return jsonResponse(TRUE,  'You are Admin !');
    });
    Route::get('makeproducttoshowcase/{product_id}', [ProductController::class, 'makeProductToShowCase']);
    Route::resource('categories', CategoriesController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});

Route::fallback(function () {

        return jsonResponse(FALSE, 'Route not found !', NULL, 404);
 
});