<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/',[\App\Http\Controllers\PagesController::class,'login']);

Auth::routes();

// admin routes
Route::group(['middleware' => ['admin'],'prefix'=>'admin'], function () {
    Route::get('/dashboard', [\App\Http\Controllers\PagesController::class, 'dashboard'])->name('admin.dashboard');
});
