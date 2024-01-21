<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstructorResource;
use App\Http\Resources\UserResource;
use App\Models\AppNotivction;
use App\Models\Enrollment;
use App\Models\User;
use App\Traits\ApiResponse;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;
    public function __construct()
    {
        // $this->middleware(['role:Admin']);
    }
    public function index(Request $request)
    {
        $users = User::all();

        return $this->success_resposnes($users);
    }


    public function store(Request $request)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {

            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $request["password"] = Hash::make($request->password);
        $user = User::create($request->all());
        $user->assignRole("Student");
        return $this->success_resposnes($user);
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->fiald_resposnes();
        }
        $user->delete();
        return $this->success_resposnes($user);
    }

    public function instructorInfo(int $id)
    {
        $user = User::with('subjects', 'subjects.subject', 'subjects.deparmentDetils.department')->find($id);
        if (is_null($user)) {
            return  $this->fiald_resposnes();
        }
        // if (!$user->hasRole("Instructor")) {
        //     return  $this->fiald_resposnes(message: "Insturctor-premition");
        // }
        return $this->success_resposnes(new InstructorResource($user));
    }

    public function studentInfo(int $id)
    {
        $user = User::with('subjects', 'subjects.subject', 'subjects.deparmentDetils.department')->find($id);
        if (is_null($user)) {
            return  $this->fiald_resposnes();
        }
        // if (!$user->hasRole("Instructor")) {
        //     return  $this->fiald_resposnes(message: "Insturctor-premition");
        // }
        return $this->success_resposnes(new InstructorResource($user));
    }


    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "name.en" => ['required', 'regex:/^[a-zA-Z ]+$/'],
            "email"  => ["required", "unique:users,email"],
            "password"  => ["required"]
        ]);
    }

    public function getNotiction (int $user_id){

        $result = AppNotivction::where("user_id",$user_id)->get();

        return $this->success_resposnes($result) ;

    }


    public function sendPushNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $usersFCM = [];

        $xx = DB::transaction(function () use ($request, &$usersFCM) {
            if (!$request->has('to')) {
                $usersFCM = User::pluck('fcm_token')->toArray(); // Fetch all users' FCM tokens


                $url = 'https://fcm.googleapis.com/fcm/send';
                $notification = [
                    'title' => $request->title,
                    'body' => $request->body
                ];
                $arrayToSend = [
                    'registration_ids' => $usersFCM, // Use 'registration_ids' instead of 'to' for multiple recipients
                    'notification' => $notification
                ];

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . env("FCM_SERVER_KEY")
                ])->post($url, $arrayToSend);
                return $this->success_resposnes($response);
            }

            // if ($request->has('to')) {
            //     appnotification::create($request->all()); // Create app notification for all users
            // }

            $url = 'https://fcm.googleapis.com/fcm/send';
            $notification = [
                'title' => $request->title,
                'body' => $request->body
            ];
            $arrayToSend = [
                'registration_ids' => $request->to, // Use 'registration_ids' instead of 'to' for multiple recipients
                'notification' => $notification
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env("FCM_SERVER_KEY")
            ])->post($url, $arrayToSend);
            // return $response;

            $result= DB::table('users')
            ->whereIn("fcm_token",$request->to)
            ->get();
            $notivect= [];
            foreach($result as $user){
                array_push($notivect,array(
                    "user_id"=>$user->id,
                    "title"=>$request->title,
                    "body"=>$request->body
                ));
            }

            if (!is_null($result)) {
                AppNotivction::insert($notivect); // Create app notification for a specific user
            }
            return $this->success_resposnes($notivect);
        });
        return $xx;
    }









    // function rule($request)
    // {

    //     $update = explode('.', Route::currentRouteName())[1] == 'update';
    //     // echo $request->id;
    //     return Validator::make($request->all(), [
    //         'id' => $update ? ['required', 'exists:bank_branches,id'] : '',
    //         'name.ar' => ['required', 'regex:/^[ء-ي ]+$/u', 'unique:bank_branches,name->ar,' . $request->id],
    //         'name.en' => ['required', 'regex:/^[a-zA-Z ]+$/', 'unique:bank_branches,name->en,' . $request->id],
    //         'code' => ['required', 'unique:bank_branches,code,' . $request->id],
    //     ]);
    // }
}
