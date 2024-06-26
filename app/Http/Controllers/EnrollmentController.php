<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowStudentResource;
use App\Models\AppNotivction;
use App\Models\Enrollment;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class EnrollmentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Enrollment::all();

        return $this->success_resposnes($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = Enrollment::create($request->all());
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return  $this->success_resposnes($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resulte = Enrollment::find($id);
        if (is_null($resulte)) {
            return $this->fiald_resposnes();
        }
        return $this->success_resposnes($resulte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = Enrollment::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->update($request->all());
        return $this->success_resposnes($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = Enrollment::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->delete();
        return $this->success_resposnes($result);
    }

    public function enrollmentStudents(int $id){

        $result =  DB::table('enrollments')
        ->join("students","enrollments.department_detile_id","=","students.department_detile_id")
        ->join("users","users.id","=","students.user_id")
        ->join("grades","grades.student_id","=","students.id")
        ->where("grades.enrollment_id","=",$id)
        ->groupBy("students.id")
        ->select(
        "grades.student_id",
        'users.name->en as name',
        'grades.final_mark'
        )
        ->get();
        return $this->success_resposnes($result);
    }

    public function test_quary(){
        $result = DB::table('enrollments')
        ->join("department_detiles","department_detiles.id","=","enrollments.department_detile_id")
        ->join("students","students.department_detile_id","=","department_detiles.id")
        ->join("users","students.user_id","=","users.id")
        ->where("enrollments.id",1)
        ->whereNotNull("users.fcm_token")
        ->select(
            "users.fcm_token"
        )->get();

        $result = DB::table("users")
        // ->where("students.enrollment_id",1)
        ->get();
        return $result->pluck('fcm_token')->toArray();
        return User::pluck('fcm_token')->toArray();
        return User::where("id",10)->pluck('fcm_token')->toArray();

        //////////////////////////////////////////////

        // $result = AppNotivction::where("user_id",2)->get();

        // return $result;
    }

    // public function students(int $id)
    // {

    //     $result = Enrollment::with("deparmentDetils.students.user")->find($id);
    //     if (is_null($result)) {
    //         return $this->fiald_resposnes();
    //     }

    //     return $this->success_resposnes(new ShowStudentResource($result));
    // }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "user_id" => ["required"],
            "subject_id" => ["required"],
            "department_detile_id" => ["required"],
            "year" => ["required"],
            "scientific_method" => ["required"],
        ]);
    }
}
