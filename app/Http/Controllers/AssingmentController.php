<?php

namespace App\Http\Controllers;

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

        return $this->success_resposnes($result);
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

        $result = Enrollment::with("assingments")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $request["enrollment_id"] = $id;
        $request["deadline"] = Carbon::createFromFormat("d/m/Y h:m:s", $request->deadline);
        $result = Assingment::create($request->all());
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return $this->success_resposnes($result);
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
    public function update(Request $request, Assingment $assingment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assingment  $assingment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assingment $assingment)
    {
        //
    }

    public function rules(Request $request)
    {
        $update = explode('.', Route::currentRouteName())[2] == 'update';


        return Validator::make($request->all(), [
            // "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "title" => [$update ? '' : "required", 'regex:/^[a-zA-Z0-9 ]+$/'],
            "description" => $update ? "" : ['required'],
            "deadline" => $update ? "" : ['required'],
            "grade" => $update ? "" : ['required', "numeric"],
        ]);
    }
}
