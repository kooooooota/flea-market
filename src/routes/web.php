<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

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
    return view('welcome');
});

Route::get('/', [ItemController::class, 'index'])->name('items.index')->middleware('verified');

Route::prefix('item')->middleware(['auth', 'verified'])->group(function () {
    Route::get('{item}', [ItemController::class, 'show'])->name('items.show');
    Route::post('{item}', [ItemController::class, 'comment'])->name('items.comment');
    Route::post('{item}/mylist', [ItemController::class, 'toggle'])->name('items.favorite');
});

Route::prefix('purchase')->middleware(['auth', 'verified'])->group(function () {
    Route::get('{item}', [ItemController::class, 'checkout'])->name('items.checkout');
    Route::post('{item}', [ItemController::class, 'purchase'])->name('items.purchase');
    Route::get('address/{item}', [ItemController::class, 'toAddressForm'])->name('items.to_address_form');
    Route::post('address/{item}', [ItemController::class, 'changeAddress'])->name('items.change_address');
});

Route::prefix('mypage')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/sell', [ItemController::class, ''])->middleware('auth');

