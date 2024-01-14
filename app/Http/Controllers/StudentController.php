<?php

namespace App\Http\Controllers;

use App\Http\Resources\showStudentone;
use App\Http\Resources\StudentResource;
use App\Models\DepartmentDetile;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class StudentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id,)
    {
        $department = DepartmentDetile::find($id);
        if (is_null($department)) {
            return $this->fiald_resposnes("not_found");
        }

        $result = DepartmentDetile::with("subjects.subject", "department")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        if (count($result->subjects) == 0) {
            return $this->fiald_resposnes("empty");
        }

        return $this->success_resposnes(new StudentResource($result));
    }

    public function studentSubjects(int $id)
    {
        $student = Student::with("grades.subject.subject")->find($id);
        if (is_null($student)) {
            return $this->fiald_resposnes("not_found");
        }
        // $result = DepartmentDetile::with("subjects.subject", "department")->find($student->department_detile_id);
        if (is_null($student)) {
            return $this->fiald_resposnes("not_found");
        }
        // if (count($result->subjects) == 0) {
        //     return $this->fiald_resposnes("empty");
        // }
        return $this->success_resposnes($student);
        // return $this->success_resposnes(new StudentResource($result));
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
        DB::beginTransaction();
        $result = Student::create($request->all());
        if (is_null($result)) {
            DB::commit();
            return $this->fiald_resposnes();
        }
        $this->addGrades($result);
        DB::commit();
        return $this->success_resposnes($result);
    }

    protected function addGrades($result)
    {
        $subjects = DepartmentDetile::find($result->department_detile_id)->subjects;
        foreach ($subjects as $subject) {
            $student = new Request();
            $student["student_id"] = $result->id;
            $student["enrollment_id"] = $subject->id;
            $addGrade = new GradeController();
            $addGrade->store($student);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(int $department_id, int $subject_id)
    {
        $result = Student::find($subject_id);
        if (is_null($result)) {
            return $this->fiald_resposnes(message: "not_found");
        }
        return $this->success_resposnes(new showStudentone($result));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $student = Student::find($id);
        $student = $student->update($request);
        if (!$student) {
            return $this->fiald_resposnes("mark_not_update" . $student);
        }

        return $this->success_resposnes($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $department_id, int $student_id)
    {
        $result = Student::find($student_id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            "user_id" => ['required', "exists:users,id", "unique:students,user_id"],
            "department_detile_id" => ['required', "exists:department_detiles,id"],
        ]);
    }
}
