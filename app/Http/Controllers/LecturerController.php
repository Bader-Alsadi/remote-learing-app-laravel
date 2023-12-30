<?php

namespace App\Http\Controllers;

use App\Http\Resources\LectuerResource;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
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
        $request["lecturer_data"] = date('Y-m-d H:i:s', strtotime($request->lecturer_data));
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
    public function update(Request $request, int $id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $result = Lecturer::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
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
    public function destroy(int $id)
    {
        $result = Lecturer::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {

        return Validator::make($request->all(), [
            // "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "title" => ['required', 'regex:/^[a-zA-Z0-9 ]+$/'],
            "description" => ['required',],
            "lecturer_data" => ['required'],
            "enrollment_id" => ['required']
        ]);
    }
}
