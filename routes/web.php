<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionController;

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
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::get('/contacts', [ContactController::class, 'index'])->middleware('auth')->name('contacts');
Route::get('/contacts/create', [ContactController::class, 'create'])->middleware('auth')->name('contacts-create');
Route::get('/transactions', [TransactionController::class, 'index'])->middleware('auth')->name('transactions');
Route::get('/transactions/create/{id}', [TransactionController::class, 'create'])->middleware('auth')->name('transactions-create');

Route::post('/contacts/store', [ContactController::class, 'store'])->middleware('auth')->name('contacts-store');
Route::post('/transactions', [TransactionController::class, 'store'])->middleware('auth')->name('transactions-store');

Route::put('/transactions/{id}', [TransactionController::class, 'update'])->middleware('auth')->name('transactions-update');

Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->middleware('auth')->name('transactions-delete');
Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->middleware('auth')->name('contacts-delete');