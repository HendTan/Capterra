<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AuthController::class)->group(function(){
    Route::get("/login", "userLoginView")->name("userLoginView");
    Route::post("/login", "login")->name("login");
    Route::get("/signup", "userLoginView")->name("userLoginView");
    Route::post("/signup","register")->name("signup");

    Route::get("/logout", "logout")->name("logout");
});

Route::controller(UserController::class)->group(function(){
    Route::get("/", "index")->name("index");
});

Route::prefix("admin")->controller(AuthController::class)->group(function(){
    Route::get("/login", "adminLoginView")->name("addminLoginView");
    Route::post("/login", "adminLogin")->name("adminLogin");
});

Route::prefix('admin')->controller(AdminController::class)->group(function(){
    Route::get("/addAdmin", "registerView")->name("registerAdmin");

    Route::post("/addAdmin", "addAdmin")->name("addAdmin");
});
