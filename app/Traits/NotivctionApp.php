<?php
namespace App\Traits;
use App\Models\AppNotivction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


trait NotivctionApp {


    // public function sendPushNotification(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [

    //         'title' => 'required|string',
    //         'body' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return $validator->errors();
    //     }

    //     $usersFCM = [];

    //     $xx = DB::transaction(function () use ($request, &$usersFCM) {
    //         if (!$request->has('to')) {
    //             $usersFCM = User::pluck('fcm_token')->toArray(); // Fetch all users' FCM tokens


    //             $url = 'https://fcm.googleapis.com/fcm/send';
    //             $notification = [
    //                 'title' => $request->title,
    //                 'body' => $request->body
    //             ];
    //             $arrayToSend = [
    //                 'registration_ids' => $usersFCM, // Use 'registration_ids' instead of 'to' for multiple recipients
    //                 'notification' => $notification
    //             ];

    //             $response = Http::withHeaders([
    //                 'Content-Type' => 'application/json',
    //                 'Authorization' => 'Bearer ' . env("FCM_SERVER_KEY")
    //             ])->post($url, $arrayToSend);
    //             return $this->success_resposnes($response);
    //         }

    //         // if ($request->has('to')) {
    //         //     appnotification::create($request->all()); // Create app notification for all users
    //         // }

    //         $url = 'https://fcm.googleapis.com/fcm/send';
    //         $notification = [
    //             'title' => $request->title,
    //             'body' => $request->body
    //         ];
    //         $arrayToSend = [
    //             'registration_ids' => $request->to, // Use 'registration_ids' instead of 'to' for multiple recipients
    //             'notification' => $notification
    //         ];

    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'Authorization' => 'Bearer ' . env("FCM_SERVER_KEY")
    //         ])->post($url, $arrayToSend);
    //         // return $response;

    //         $result= DB::table('users')
    //         ->whereIn("fcm_token",$request->to)
    //         ->get();
    //         $notivect= [];
    //         foreach($result as $user){
    //             array_push($notivect,array(
    //                 "user_id"=>$user->id,
    //                 "title"=>$request->title,
    //                 "body"=>$request->body
    //             ));
    //         }

    //         if (!is_null($result)) {
    //             AppNotivction::insert($notivect); // Create app notification for a specific user
    //         }
    //         return $this->success_resposnes($notivect);
    //     });


        //     $user = User::whereIn('fcm_token', $request->to);

        //     if (!is_null($user)) {
        //         // $request['user_id'] = $user->id;
        //         // echo $request['user_id'];
        //         AppNotivction::create($request->all()); // Create app notification for a specific user
        //     }
        //     return $this->success_resposnes($user);


        //     // return $response;
        // });
//         return $xx;
//     }


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


    public function getStudentIds (int $enrollment_id){
        $result = DB::table('enrollments')
        ->join("department_detiles","department_detiles.id","=","enrollments.department_detile_id")
        ->join("students","students.department_detile_id","=","department_detiles.id")
        ->join("users","students.user_id","=","users.id")
        ->where("enrollments.id",$enrollment_id)
        ->whereNotNull("users.fcm_token")
        ->select(
            "users.fcm_token"
        )->get();
        return $result->pluck('fcm_token')->toArray();
    }

}

