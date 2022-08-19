<?php

namespace App\Http\Controllers\FrontendController;

use App\Http\Controllers\Controller;
use App\Models\AppUserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    /**User store*/
    public function StoreUSer(Request $request){
        $validateInputData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|max:255',
        ]);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = $first_name.' '.$last_name;
        $email = $request->email;
        $password = $request->password;
        $role = 'User';
        $hashed_password = Hash::make($password);
        User::insert([
            'name' => $full_name,
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => Carbon::now()
        ]);
        AppUserRole::insert([
            'name' => $full_name,
            'email' => $email,
            'role' => $role,
            'created_at' => Carbon::now()
        ]);
        return redirect()->route('login')->with('successfully registered', 'You have successfully registered');
    }

    /**Rider store */
    public function StoreRider(Request $request){
        $validateInputData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|max:255',
        ]);
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = $first_name . ' ' . $last_name;
        $email = $request->email;
        $password = $request->password;
        $role = 'Rider';
        $hashed_password = Hash::make($password);
        User::insert([
            'name' => $full_name,
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => Carbon::now()
        ]);
        AppUserRole::insert([
            'name' => $full_name,
            'email' => $email,
            'role' => $role,
            'created_at' => Carbon::now()
        ]);
        return redirect()->route('login')->with('successfully registered', 'You have successfully registered');
    }
}
