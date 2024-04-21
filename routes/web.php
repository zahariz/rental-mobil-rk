<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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


// Auth
Route::group(['middleware' => ['guest']], function (){
    Route::get('/login',[UserController::class, 'login'])->name('login');
    Route::post('/login',[UserController::class, 'authenticate'])->name('login.auth');
    Route::get('/register',[UserController::class, 'register'])->name('register');
    Route::post('/register',[UserController::class, 'storeRegister'])->name('register.store');
});


Route::group(['middleware' => ['auth']], function () {
    Route::delete('/logout',[UserController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');

    // List Mobil
    Route::get('/list-cars',[CarController::class, 'list'])->name('cars.list');


    // Transaction
    Route::get('/transaction',[TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/rent/{id}/cars',[TransactionController::class, 'rental'])->name('transaction.rental')->where('id', '[0-9]+');
    Route::post('/rent/{id}/cars',[TransactionController::class, 'storeRental'])->name('transaction.rental.store')->where('id', '[0-9]+');
    Route::get('/return',[TransactionController::class, 'return'])->name('transaction.return');
    Route::get('/return/{id}/cars',[TransactionController::class, 'createReturn'])->name('transaction.return.create')->where('id', '[0-9]+');
    Route::put('/return/{id}/cars',[TransactionController::class, 'storeReturn'])->name('transaction.return.store')->where('id', '[0-9]+');

    Route::get('/users/{id}/edit',[UserController::class, 'edit'])->name('users.edit')->where('id', '[0-9]+');
    Route::put('/users/{id}/edit',[UserController::class, 'updateUser'])->name('users.update')->where('id', '[0-9]+');
    Route::put('/contact/{id}/edit',[UserController::class, 'updateContact'])->name('contact.update')->where('id', '[0-9]+');
    Route::group(['middleware' => ['cek_login:Admin']], function(){
        // Master Car
        Route::get('/cars',[CarController::class, 'index'])->name('cars.index');
        Route::get('/cars/create',[CarController::class, 'create'])->name('cars.create');
        Route::post('/cars/create',[CarController::class, 'store'])->name('cars.store');

        // User
        Route::get('/users',[UserController::class, 'user'])->name('users.index');
        Route::get('/users/create',[UserController::class, 'create'])->name('users.create');
    });
});






