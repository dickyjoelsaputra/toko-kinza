<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/register', function () {
//     $data['name'] = 'superadmin';
//     $data['username'] = 'admin';
//     $data['password'] = Hash::make('admin');
//     User::create($data);
// });


Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(
    function () {
        // DASHBOARD START
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard-chart', [DashboardController::class, 'ajaxChart'])->name('dashboard-ajaxchart');
        // DASHBOARD END

        // UNIT START
        Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
        Route::get('/unit/{id}', [UnitController::class, 'show'])->name('unit.show');
        Route::post('/unit-create', [UnitController::class, 'ajaxCreate'])->name('unit.create');
        Route::delete('/unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');
        Route::put('/unit/{id}', [UnitController::class, 'update'])->name('unit.update');
        // UNIT END

        // CATEGORY START
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
        Route::post(
            '/category-create',
            [
                CategoryController::class, 'ajaxCreate'
            ]
        )->name('category.create');
        Route::delete(
            '/category/{id}',
            [
                CategoryController::class, 'destroy'
            ]
        )->name('category.destroy');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
        // CATEGORY END

        // ITEM START
        Route::get('/item', [ItemController::class, 'index'])->name('item.index');
        Route::get('/item-ajax', [ItemController::class, 'ajaxIndex'])->name('item.ajax');
        Route::delete('item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
        Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/item/create-ajax', [ItemController::class, 'createAjax'])->name('item.create-ajax');
        Route::get('/item/{id}', [ItemController::class, 'edit'])->name('item.edit');
        Route::put('/item/{id}', [ItemController::class, 'update'])->name('item.update');
        // ITEM END

        // CASHIER START
        Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
        Route::post('/cashier-scan', [CashierController::class, 'cashierScan'])->name('cashier.scan');
        Route::post('/cashier/search', [CashierController::class, 'search'])->name('cashier.search');
        Route::post('/cashier/getItem', [CashierController::class, 'getItem'])->name('cashier.getItem');
        // CASHIER END

        // TRANSACTION START
        Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
        Route::post('/transaction/ajaxStore', [TransactionController::class, 'ajaxStore'])->name('transaction.ajaxStore');
        // TRANSACTION END

        // USER START
        Route::get('/user', [UserController::class, 'index'])->name('user.index');

        // USER END

    }
);
