<?php

namespace App\Http\Controllers;

use App\Http\Resources\LectuerResource;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class LecturerController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $result = Enrollment::with("lecturers")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        return $this->success_resposnes(new LectuerResource($result));
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
        $department = Enrollment::find($request->enrollment_id);
        if (is_null($department)) {
            return $this->fiald_resposnes("not_found");
        }
        $request["lecturer_data"] = Carbon::createFromFormat("Y-m-d", $request->lecturer_data);
        // date('Y-m-d', strtotime($request->lecturer_data));
        $department = Lecturer::create($request->all());
        if (is_null($department)) {
            return $this->fiald_resposnes();
        }

        return  $this->success_resposnes($department);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resulte = Lecturer::find($id);
        if (is_null($resulte)) {
            return $this->fiald_resposnes();
        }
        return $this->success_resposnes($resulte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $lecturerID)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }

        $result = Enrollment::find($request->enrollment_id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $result = Lecturer::find($lecturerID);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        $result->update($request->all());
        return $this->success_resposnes($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $enrollmentId, int $lecturerID)
    {


        $result = Enrollment::find($enrollmentId);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $result = Lecturer::find($lecturerID);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {
        $update =  Route::currentRouteName() == 'updateLecturer';


        return Validator::make($request->all(), [
            // "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "title" => [$update ? '' : "required", 'regex:/^[a-zA-Z0-9 ]+$/'],
            "description" => $update ? "" : ['required'],
            "lecturer_data" => $update ? "" : ['required',],
            "enrollment_id" => ['required', "exists:enrollments,id"],
        ]);
    }
}
