<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

use function PHPUnit\Framework\returnSelf;

class AuthContoller extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {

        // $validtion = $this->rules($request);
        // if ($validtion->fails()) {
        //     return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        // }
        $user = User::whereEmail($request->email)->first();
        if (is_null($user)) {
            return $this->fiald_resposnes(message: "Not_found");
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->fiald_resposnes(message: "Password wrong");
        }
        $user->token = $user->createToken('token-api')->plainTextToken;
        $user->role_type = $user->roles->first()->name;
        if ($user->hasRole("Student")) {
            $student = Student::with("department")->whereUserId($user->id)->first();
            $user->student = $student;
        }
        return $this->success_resposnes(new UserResource($user));
    }

    public function logout()
    {
        $user = Auth::user();
        if (is_null($user)) {
            return $this->fiald_resposnes(message: "not_found");
        } else {
            $currentAccessToken = $user->currentAccessToken()->delete();
            return $this->success_resposnes($user, message: "deleted");
        }
    }



    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "email"  => ["required", "email", "exists:users,email"],
            "password"  => ["required"]
        ]);
    }
}
