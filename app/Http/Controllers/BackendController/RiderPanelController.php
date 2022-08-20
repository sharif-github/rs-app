<?php

namespace App\Http\Controllers\BackendController;

use App\Http\Controllers\Controller;
use App\Models\VehicleDetail;
use App\Models\VehicleMultiImage;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Mockery\Matcher\Ducktype;

class RiderPanelController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function ViewPanel(){
        $serach_vehicle = request()->query('car_query');
        $author = Auth::user()->email;
        $fetch_user_details = DB::table('app_user_roles')
        ->where('email', '=', Auth::user()->email)
            ->get();
        foreach ($fetch_user_details as $user_details) {
            $user_status = $user_details->role;
        }
        if (!empty($serach_vehicle)) {
            $vehicle_list = DB::table('vehicle_details')
            ->where('model', 'LIKE', "%$serach_vehicle%")
            ->where('author', '=', $author)
                ->orderBy('vehicle_id', 'DESC')
                ->paginate('4');
        } else {
            $vehicle_list = DB::table('vehicle_details')
            ->where('author', '=', $author)
                ->orderBy('vehicle_id', 'DESC')
                ->paginate('4');
        }
        $vehicle_type_list = DB::table('vehicle_types')
        ->get();
        return view('pages.backend.dashboard.helping-page.rider.vehicle-panel',compact('user_status', 'vehicle_list', 'vehicle_type_list'));
    }

    public function StoreVehicle(Request $request){
        $validateInputData = $request->validate([
            'mileage' => 'required|max:255',
            'plate' => 'required|max:255|unique:vehicle_details,plate',
            'condition' => 'required|max:255',
            'model' => 'required|max:255',
            'seats' => 'required|numeric|min:2|max:255',
            'color' => 'required|max:255',
            'type' => 'required|max:255',
            'main_image' => 'required|mimes:jpg,jpeg,png',
            'multi_image.*' => 'mimes:jpg,jpeg,png',
        ]);
        $input_mileage = $request->mileage;
        $mileage = $input_mileage.' KM';
        $plate = $request->plate;
        $condition = $request->condition;
        $model = $request->model;
        $type = $request->type;
        $seats = $request->seats;
        $color = $request->color;
        $main_image = $request->main_image;
        $multi_image = $request->multi_image;
        $author = Auth::user()->email;
        $request_date =  date('d-m-Y');
        $vehicle_id = IdGenerator::generate(['table' => 'vehicle_details', 'field' => 'vehicle_id', 'length' => 12, 'prefix' => 'VN-']);
         if(!empty($main_image)){
                    $file_name_gen = hexdec(uniqid()).'.'. $main_image->getClientOriginalExtension();
                    Image::make($main_image)->resize(400,400)->save('public/service_assets/vehicle_image/'.$file_name_gen);
                    $final_car_image = '/public/service_assets/vehicle_image/'.$file_name_gen;
                    VehicleDetail::insert([
                    'vehicle_id' => $vehicle_id,
                    'mileage' => $mileage,
                    'plate' => $plate,
                    'condition' => $condition,
                    'model' => $model,
                    'type' => $type,
                    'seats' => $seats,
                    'color' => $color,
                    'main_image' => $final_car_image,
                    'author' => $author,
                     'created_at' => Carbon::now()
                    ]);
         }else{
            VehicleDetail::insert([
                'vehicle_id' => $vehicle_id,
                'mileage' => $mileage,
                'plate' => $plate,
                'condition' => $condition,
                'model' => $model,
                'seats' => $seats,
                'color' => $color,
                'created_at' => Carbon::now()
            ]);
         }
        if (!empty($multi_image)) {
            for ($count = 0; $count < count($multi_image); $count++) {
                $file_name_gen = hexdec(uniqid()) . '.' . $multi_image[$count]->getClientOriginalExtension();
                Image::make($multi_image[$count])->resize(720, 1280)->save('public/service_assets/vehicle_image/internal/' . $file_name_gen);
                $final_car_interior_path = '/public/service_assets/vehicle_image/internal/' . $file_name_gen;
                VehicleMultiImage::insert([
                    'vehicle_id' => $vehicle_id,
                    'img_description' => $model,
                    'img_path' => $final_car_interior_path,
                    'created_at' => Carbon::now()
                ]);
            }
        }
        $data=['author'=> $author,
            'car_model' => $model,
            'plate' => $plate,
            'date' => $request_date
            ];
        $user['to']='admin@ride_share.com';
        Mail::send('emails.vehicle-mail',$data,function($messages)use($user){
            $messages->to($user['to']);
            $messages->subject('Request for vehicle approval');
        });
        return redirect()->route('vehicle.registrationPanel')->with('success', 'Vehicle Registration Successfull Waiting for Aproval');
    }
}
