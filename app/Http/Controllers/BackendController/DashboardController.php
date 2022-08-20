<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function DashboardRouting(){
        $fetch_user_details = DB::table('app_user_roles')
            ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        if ($user_status === 'User'){
            $fetch_city = DB::table('cities_tables')
                ->get();
            $fetch_vehicle_type = DB::table('vehicle_types')
                ->get();
            return view('pages.backend.dashboard.user', compact('user_status', 'fetch_city', 'fetch_vehicle_type'));
        }elseif($user_status === 'Rider'){
            $fetch_city = DB::table('cities_tables')
            ->get();
            $vehicle_details = DB::table('vehicle_details')
                ->where('author', '=', Auth::user()->email)
                ->where('status', '=', 'Active')
            ->get();
            return view('pages.backend.dashboard.rider', compact('user_status', 'fetch_city', 'vehicle_details'));
        }elseif($user_status === 'Admin'){
            return view('pages.backend.dashboard.admin', compact('user_status'));
        }
    }
}
