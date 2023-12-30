<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstructorResource;
use App\Http\Resources\UserResource;
use App\Models\Enrollment;
use App\Models\User;
use App\Traits\ApiResponse;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $user->assignRole("Instructor");
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


    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "name.en" => ['required', 'regex:/^[a-zA-Z ]+$/'],
            "email"  => ["required", "unique:users,email"],
            "password"  => ["required"]
        ]);
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
