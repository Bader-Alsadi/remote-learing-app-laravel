<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\DepartmentDetile;
use App\Models\Student;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class StudentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $studen = Student::where("user_id", $id)->first();
        if (is_null($studen)) {
            return $this->fiald_resposnes("not_found");
        }

        $result = DepartmentDetile::with("subjects.subject", "department")->find($studen->department_detile_id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        if (count($result->subjects) == 0) {
            return $this->fiald_resposnes("empty");
        }

        return $this->success_resposnes(new StudentResource($result));
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
        $result = Student::create($request->all());
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return $this->success_resposnes($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            // "user_id" => ['required',"exists:users"],
            // "department_detile_id" => ['required',"exists:department_detiles"],
        ]);
    }
}
