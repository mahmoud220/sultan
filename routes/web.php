<?php

use App\Http\Controllers\{
    CategoriesController,
    ProductController,
    MemberController,
    SupplierController,
    ExpenseController,
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
    //Categories
    Route::get('/category/data', [CategoriesController::class, 'data'])->name('category.data');
    Route::resource('/categories', CategoriesController::class);

    //Products
    Route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
    Route::post('/product/delete-selected', [ProductController::class, 'deleteSelected'])->name('product.delete_selected');
    Route::post('/product/print-barcode', [ProductController::class, 'printBarcode'])->name('product.print_barcode');
    Route::resource('/products', ProductController::class);

    //Memebrs
    Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
    Route::post('/member/delete-selected', [MemberController::class, 'deleteSelected'])->name('member.delete_selected');
    Route::post('/member/print-member', [MemberController::class, 'printMember'])->name('member.print_member');
    Route::resource('/members', MemberController::class);

    //Suppliers
    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/suppliers', SupplierController::class);

    //Expenses
    Route::get('/expense/data', [ExpenseController::class, 'data'])->name('expense.data');
    Route::resource('/expenses', ExpenseController::class);
});
