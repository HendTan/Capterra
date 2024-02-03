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
    Route::get("/start", "start")->name("start");
    Route::get("/getTask", "getTask")->name("getTask");
    Route::get("/record/{id}", "record")->name("record");
    Route::get("/service", "service")->name("service");
    Route::get("/profile", "profile")->name("profile");

    Route::post("/submitTask/{id}", "submitTask")->name("submitTask");
    Route::post("/submitWithdrawInfo", "submitWithdrawInfo")->name("submitWithdrawInfo");
    Route::post("/submitWithdraw", "submitWithdraw")->name("submitWithdraw");
});

Route::prefix("admin")->controller(AuthController::class)->group(function(){
    Route::get("/login", "adminLoginView")->name("addminLoginView");
    Route::post("/login", "adminLogin")->name("adminLogin");
    Route::post("/logout", "adminLogout")->name("adminLogout");
});

Route::prefix('admin')->controller(AdminController::class)->group(function(){
    Route::get("/memberList", "memberList")->name("memberList");
    Route::get("/addAdmin", "registerView")->name("registerAdmin");
    Route::get("/getUserInfo/{id}", "getUserInfo")->name("getUserInfo");
    Route::get("/ban/{id}", "banUser")->name("banUser");
    Route::get("/unban/{id}", "unbanUser")->name("unbanUser");
    Route::get("/productList", "productList")->name("productList");
    Route::get("/getProductInfo/{id}", "getProductInfo")->name("getProductInfo");
    Route::get("/deleteProduct/{id}", "deleteProduct")->name("deleteProduct");
    Route::get("/memberVip", "memberVip")->name("memberVip");
    Route::get("/addVip", "addVipView")->name("addVipView");
    Route::get("/getVipInfo/{id}", "getVipInfo")->name("getVipInfo");
    Route::get("/deleteVip/{id}", "deleteVip")->name("deleteVip");
    Route::get("/customerService","customerService")->name("customerService");
    Route::get("/getCSInfo/{id}", "getCSInfo")->name("getCSInfo");
    Route::get("/productSearch", "productSearch")->name("productSearch");
    Route::get("/deleteCS/{id}", "deleteCS")->name("deleteCS");
    Route::get("/dealControl", "dealControl")->name("dealControl");
    Route::get("/addDealControl", "addDealControlView")->name("addDealControlView");
    Route::get("/withdrawManage", "withdrawManage")->name("withdrawManage");
    Route::get("/approve/{id}", "approve")->name("approve");
    Route::get("/reject/{id}", "reject")->name("reject");
    Route::get("/renewTask/{id}", "renewTask")->name("renewTask");

    Route::post("/addAdmin", "addAdmin")->name("addAdmin");
    Route::post("/editBalance", "editBalance")->name("editBalance");
    Route::post("/adminEditUser", "adminEditUser")->name("adminEditUser");
    Route::post("/adminEditLogin", "adminEditLogin")->name("adminEditLogin");
    Route::post("/adminEditWithdraw", "adminEditWithdraw")->name("adminEditWithdraw");
    Route::post("/addProduct", "addProduct")->name("addProduct");
    Route::post("/editProduct", "editProduct")->name("editProduct");
    Route::post("/editVip", "editVip")->name("editVip");
    Route::post("/addCS", "addCS")->name("addCS");
    Route::post("/editCS", "editCS")->name("editCS");
    Route::post("/addMember", "addMember")->name("addMember");
    
    Route::post("/addTask", "addTask")->name("addTask");
    Route::post("/editCredit", "editCredit")->name("editCredit");
    Route::post("/addTasks", "addTasks")->name("addTasks");
    Route::post("/addVip", "addVip")->name("addVip");
    Route::post("/addDealControl", "addDealControl")->name("addDealControl");
    Route::post("/editDealControl", "editDealControl")->name("editDealControl");
});
