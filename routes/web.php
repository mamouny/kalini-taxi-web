<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CourseController;

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

Route::get('lang/change', [PagesController::class, 'changeLanguage'])->name('changeLanguage');

Route::get('/',[PagesController::class,'login']);

Route::middleware(['middleware' => 'prevent-back-history'])->group(function () {
    Auth::routes();
});

// admin routes
Route::middleware(['admin', 'prevent-back-history'])->prefix('admin')->group(function () {
    // Dashboard route
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('admin.dashboard');

    // Drivers routes
    Route::get("/drivers",[DriverController::class,'index'])->name('admin.drivers');
    Route::get("/drivers/create",[DriverController::class,'create'])->name('admin.drivers.create');
    Route::post("/drivers",[DriverController::class,'store'])->name('admin.drivers.store');
    Route::get("/drivers/{id}/edit",[DriverController::class,'edit'])->name('admin.drivers.edit');
    Route::put("/drivers/{id}",[DriverController::class,'update'])->name('admin.drivers.update');
    Route::delete("/drivers/{id}",[DriverController::class,'destroy'])->name('admin.drivers.destroy');
    Route::get("/drivers/{id}/show",[DriverController::class,'show'])->name('admin.drivers.show');
    Route::post("/drivers/{id}/add-car",[DriverController::class,'storeCar'])->name('admin.drivers.store-car');
    Route::put("/drivers/{id}/update-car",[DriverController::class,'updateCar'])->name('admin.drivers.update-car');
    Route::put("/drivers/{id}/update-state",[DriverController::class,'changeDriverState'])->name('admin.drivers.update-state');
    Route::post("/drivers/{id}/add-wallet",[DriverController::class,'storeWallet'])->name('admin.drivers.add-wallet');

    // Courses routes
    Route::get("/courses",[CourseController::class,'index'])->name('admin.courses');
    Route::post("/courses",[CourseController::class,'store'])->name('admin.courses.store');
    Route::put("/courses/{id}/cancel",[CourseController::class,'cancelCourse'])->name('admin.courses.cancel');
    Route::delete("/courses/{id}",[CourseController::class,'destroy'])->name('admin.courses.destroy');
    Route::get("/courses/get-prix-km/{id}/{km}",[CourseController::class,'getPrixKm'])->name('admin.courses.get-prix-km');
    Route::get("/courses/get-drivers", [CourseController::class,'getDrivers'])->name('admin.get-drivers');

    // Clients resource route
    Route::resource('clients', ClientController::class);
});

