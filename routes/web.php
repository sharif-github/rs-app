<?php

use App\Http\Controllers\BackendController\DashboardController;
use App\Http\Controllers\BackendController\RiderPanelController;
use App\Http\Controllers\FrontendController\PageItemController;
use App\Http\Controllers\FrontendController\StoreController;
use App\Http\Controllers\SystemAppPayloadController;
use Illuminate\Support\Facades\Route;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', [DashboardController::class, 'DashboardRouting'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'DashboardRouting'])->name('/');
});
/**Dev routes */
Route::get('/register', [PageItemController::class, 'RegisterPageRoute'])->name('register');
Route::get('/register/rider', [PageItemController::class, 'RegisterRiderPageRoute'])->name('register.rider');
Route::post('/store/user', [StoreController::class, 'StoreUSer'])->name('store.user');
Route::post('/store/rider', [StoreController::class, 'StoreRider'])->name('store.rider');
//Log out
Route::get('/log/out', [DashboardController::class, 'Logout'])->name('logout');
/**Helping Routes */
Route::get('/fetch/to/city',[SystemAppPayloadController::class,'FetchFromCity'])->name('fetch.toCity');

/**Internal Controller */
Route::prefix('/rider/panel')->group(function(){
    Route::get('/view',[RiderPanelController::class,'ViewPanel'])->name('vehicle.registrationPanel');
    Route::post('/store/vehicle',[RiderPanelController::class,'StoreVehicle'])->name('store.vehicle');
});

