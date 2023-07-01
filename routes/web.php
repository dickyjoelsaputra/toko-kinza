<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;

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
        Route::get('/', [DashboardController::class, 'index']);
        // UNIT START
        Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
        Route::get('/unit/{id}', [UnitController::class, 'show'])->name('unit.show');
        Route::post('/unit-create', [UnitController::class, 'ajaxCreate'])->name('unit.create');
        Route::delete('/unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');
        Route::put('/unit/{id}', [UnitController::class, 'update'])->name('unit.update');
        // UNIT END

        // ITEM START
        Route::get('/item', [ItemController::class, 'index'])->name('item.index');
        Route::get('/item-ajax', [ItemController::class, 'ajaxIndex'])->name('item.ajax');
        Route::delete('item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
        Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/item/create-ajax', [ItemController::class, 'createAjax'])->name('item.create-ajax');
        // ITEM END
    }
);
