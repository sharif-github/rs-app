<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemAppPayloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function FetchFromCity(Request $request)
    {
        $selected_from_city = $request->selected_from_city;
        if(empty($selected_from_city)){
            $fetch_to_city = 'Select your starting point first';
        }else{
            $fetch_to_city = DB::table('cities_tables')
            ->where('name', '!=', $selected_from_city)
            ->get();
        }
        return response()->json([
            'fetched_to_cities' => $fetch_to_city
        ], 200);
    }
}
