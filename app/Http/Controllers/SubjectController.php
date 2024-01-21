<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Traits\ApiResponse;
use App\Traits\UpLoadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    use ApiResponse;
    use UpLoadImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Subject::all();

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
        $request["image"] = $this->uploadeFile($request->file("file"), "subjects");
        $result = Subject::create($request->except("file"));
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }

        return  $this->success_resposnes($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resulte = Subject::find($id);
        if (is_null($resulte)) {
            return $this->fiald_resposnes();
        }
        return $this->success_resposnes($resulte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }
        $request["image"] = $this->uploadeFile($request->file("file"), "subjects");
        $result = Subject::find($id);
        $result->update($request->all());
        return $this->success_resposnes($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = Subject::find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes();
        }
        $result->delete();
        return $this->success_resposnes($result);
    }

    public function rules(Request $request)
    {
        $update = Route::currentRouteName() == 'updateSubject';
        return Validator::make($request->all(), [
            "id" => $update ? [ "exists:subjects,id"] : "",
            "name" => $update ? "" : ["required"],
            "houre" => [$update ? "" : "required", "numeric"],
            "file" => [$update ? "" : "required",],
            "grade" => [$update ? "" : "required", "numeric", "max:100"]
        ]);
    }
}
