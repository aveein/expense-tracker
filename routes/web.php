<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
})->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('date-wise/transaction',[App\Http\Controllers\HomeController::class,'getDateWise'])->name('transactions.date-wise');

Route::resource('categories',CategoryController::class);
Route::resource('transactions',TransactionController::class);
Route::post('categories/data',[CategoryController::class,'data'])->name('categories.data');
Route::post('transactions/data',[TransactionController::class,'data'])->name('transactions.data');

Route::get('test',function(){
    return view('test');
});
