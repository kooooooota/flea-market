<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/purchase/success', [PurchaseController::class, 'success'])
    ->name('purchase.success')
    ->middleware('auth');
Route::post('/Purchase/checkout/{id}', [PurchaseController::class, 'checkout'])
    ->name('purchase.checkout')
    ->middleware('auth');
Route::get('/purchase/cancel/{id}', function($id) {
    return redirect()->route('items.show', $id)->with('message', '決済がキャンセルされました');
})->name('purchase.cancel');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('item/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('item/{item}', [ItemController::class, 'comment'])->name('items.comment')->middleware(['auth', 'verified']);
Route::post('item/{item}/mylist', [ItemController::class, 'toggle'])->name('items.favorite')->middleware(['auth', 'verified']);

Route::prefix('purchase')->middleware(['auth', 'verified'])->group(function () {
    Route::get('{item}', [ItemController::class, 'checkout'])->name('items.checkout');
    Route::get('address/{item}', [ItemController::class, 'toAddressForm'])->name('items.to_address_form');
    Route::post('address/{item}', [ItemController::class, 'changeAddress'])->name('items.change_address');
});

Route::prefix('mypage')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/sell', [ItemController::class, 'exhibit'])->name('items.exhibit')->middleware('auth');
Route::post('/sell', [ItemController::class, 'sell'])->name('items.sell')->middleware('auth');



