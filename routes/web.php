<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
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

Route::get('/index', function () {
    return view('index');
});

Route::get('/kendo', function () {
    return view('kendo.index');
});


Route::get('/data-details', [App\Http\Controllers\OrderController::class, 'orderData']);
Route::delete('/order/destroy/{id}',  [App\Http\Controllers\OrderController::class, 'destroy'])->name('orderDestory');


