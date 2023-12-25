<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class AuthContollerController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {

        $validtion = $this->rules($request);
        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors());
        }
        $user = User::whereEmail($request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            $user->tooken = $user->createToken('token-api')->plainTextToken;
            return $this->success_resposnes($user);
        }
        return $this->success_resposnes($user);
    }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "email"  => ["required", "exists:users,email"],
            "password"  => ["required"]
        ]);
    }
}
