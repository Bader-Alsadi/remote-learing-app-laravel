<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssingmentResource;
use App\Models\Assingment;
use App\Models\Enrollment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
class AssingmentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $result = Enrollment::with("assingments")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        if (count($result->assingments) == 0) {
            return $this->fiald_resposnes(message: "empty");
        }

        return $this->success_resposnes(new AssingmentResource($result));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        // App\Models\Enrollment::with("subject")->find(1);
        $result = Enrollment::with("assingments", "subject")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        if ($result->subject->grade < ($request->grade + $this->sumAllGrades($result))) {
            return $this->fiald_resposnes("the subject is than the sum of all grade");
        }
        $request["enrollment_id"] = $id;
        $request["deadline"] = Carbon::createFromFormat("Y-m-d", $request->deadline);
        $result = Assingment::create($request->all());
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return $this->success_resposnes($result);
    }

    public function sumAllGrades($result)
    {
        $total = 0;
        foreach ($result->assingments as $item) {
            $total += $item->grade;
        }

        return $total;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Assingment  $assingment
     * @return \Illuminate\Http\Response
     */
    public function show(Assingment $assingment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assingment  $assingment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $assingmentID)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }

        $result = Enrollment::find($request->enrollment_id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $result = Assingment::find($assingmentID);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        $result->update($request->all());
        return $this->success_resposnes($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assingment  $assingment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $enrollmentId, int $assingmetID)
    {


        $result = Enrollment::find($enrollmentId);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $result = Assingment::find($assingmetID);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {
        $update =  Route::currentRouteName() == 'updateAssingment';


        return Validator::make($request->all(), [
            // "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "title" => [$update ? '' : "required", 'regex:/^[a-zA-Z0-9 ]+$/'],
            "description" => $update ? "" : ['required'],
            "deadline" => $update ? "" : ['required', "after_or_equal:today"],
            "grade" => $update ? "" : ['required', "numeric", "max:100", "min:0"],
            "enrollment_id" => ['required', "exists:enrollments,id"],
        ]);
    }
}
