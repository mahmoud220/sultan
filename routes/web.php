<?php

use App\Http\Controllers\{
    CategoriesController,
    ProductController,
};
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
    return redirect()->route('login');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('home');
})->name('dashboard');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/category/data', [CategoriesController::class, 'data'])->name('category.data');
    Route::resource('/categories', CategoriesController::class);
    Route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
    Route::resource('/products', ProductController::class);
});
