<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Models\Lecturer;
use App\Models\Material;
use App\Traits\ApiResponse;
use App\Traits\UpLoadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    use ApiResponse;
    use UpLoadImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $result = Lecturer::with("matirels")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        return $this->success_resposnes(new MaterialResource($result));
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
        $lecturer = Lecturer::find($request->lecturer_id);
        if (is_null($lecturer)) {
            return $this->fiald_resposnes("not_lecturer_found");
        }
        if (!$request->has("file")) {
            return $this->fiald_resposnes("path_not_found");
        } else {

            $strogePath = $this->uploadeFile($request->file("file"), $lecturer->title, $lecturer->id);
            if (is_null($strogePath)) {
                return $this->fiald_resposnes("path_not_storge");
            } else {
                $request["size"] = $request->file('file')->getSize() / (1024 * 1024);
                $request["madia_type"] = $request->file('file')->getClientOriginalExtension();
                $request["path"] = $strogePath;
                $material = Material::create($request->except("file"));
                if (is_null($material)) {
                    return $this->fiald_resposnes();
                }

                return  $this->success_resposnes($material);
            }
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resulte = Material::find($id);
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
    public function update(Request $request, int $LecturerId, int $MaterialID)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }

        $result = Lecturer::find($LecturerId);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_Lecturer_found");
        }
        $result = Material::find($MaterialID);
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
    public function destroy(int $LecturerId, int $MaterialID)
    {


        $result = Lecturer::find($LecturerId);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }
        $result = Material::find($MaterialID);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {
        $update = explode('.', Route::currentRouteName())[2] == 'update';


        return Validator::make($request->all(), [
            // "name.ar" => ["required", 'regex:/^[ء-ي ]+$/u'],
            "title" => [$update ? '' : "required", 'regex:/^[a-zA-Z0-9 ]+$/'],
            "type" => $update ? "" : ['required'],
            "lecturer_id" => $update ? "" : ['required'],
            "file" => $update ? "" : ['required', "mimes:jpg,jpeg,png,pdf,xls,doc,docm,docx,dot,pptx,rar,zip,txt,mp4,webm"],
        ]);
    }
}
