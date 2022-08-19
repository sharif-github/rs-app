<?php

use App\Http\Controllers\BackendController\DashboardController;
use App\Http\Controllers\FrontendController\PageItemController;
use App\Http\Controllers\FrontendController\StoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
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
    Route::get('/', function () {
        $fetch_user_details = DB::table('app_user_roles')
            ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        return view('pages.backend.dashboard.dashboard',compact('user_status'));
    })->name('dashboard');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $fetch_user_details = DB::table('app_user_roles')
            ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        return view('pages.backend.dashboard.dashboard', compact('user_status'));
    });
});
/**Dev routes */
Route::get('/register',[PageItemController::class,'RegisterPageRoute'])->name('register');
Route::get('/register/rider', [PageItemController::class, 'RegisterRiderPageRoute'])->name('register.rider');
Route::post('/store/user',[StoreController::class,'StoreUSer'])->name('store.user');
Route::post('/store/rider', [StoreController::class, 'StoreRider'])->name('store.rider');
//Log out
Route::get('/log/out', [DashboardController::class, 'Logout'])->name('logout');
