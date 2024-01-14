<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Double;

class GradeController extends Controller
{

    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $student = Student::with("grades")->find($id);
        if (is_null($student)) {
            return $this->fiald_resposnes(message: "not_found");
        }

        return $this->success_resposnes($student);
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
        $grade = Grade::create($request->all());
        if (is_null($grade)) {
            return $this->fiald_resposnes(message: "not_created");
        }
        return $this->success_resposnes($grade, message: "added_succssuful");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $student_id, int $grade_id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }

        $grade = Grade::find($grade_id);
        if ($grade) {
            $grade = $grade->update($request->all());
            return $this->success_resposnes($grade, message: "update_succssuful");
        }
        return $this->fiald_resposnes(message: "not_updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $student_id, int $grade_id)
    {
        $grade = Grade::find($grade_id);
        if ($grade) {
            $grade = $grade->delete();
            return $this->success_resposnes($grade);
        }

        return $this->fiald_resposnes(message: "not_delete");
    }

    public function updateGrade(Request $request)
    {
        // $request = new Request(array(
        //     "enrollment_id" => $enrollment_id,
        //     "student_id" => $student_id,
        //     "fianl_mark" => $mark
        // ));
        $validtion = $this->rules($request);
        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = Grade::whereStudentId($request->student_id)->whereEnrollmentId($request->enrollment_id)->first();

        if (is_null($result)) {
            return  $this->store($request);
        } else {
            return $this->update($request, $request->student_id, $result->id);
        }
    }


    public function rules(Request $request)
    {
        // $update = explode(".", Route::currentRouteName())[2] == 'update';


        return Validator::make($request->all(), [
            "student_id" =>  ['required', "exists:students,id"],
            "enrollment_id" => ['required', "exists:enrollments,id"],
            "final_mark" =>   ["numeric", "max:100", "min:0,gt:subject,grade"],
        ]);
    }
}
