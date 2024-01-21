<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResouce;
use App\Models\Assingment;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Submission;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class SubmissionController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $result = Assingment::with("submissions.student.user")->find($id);
        if (is_null($result)) {
            return $this->fiald_resposnes("not_found");
        }

        // $result["student_name"] = $result->submissions->student->user->name[app()->getLocale()];
        return $this->success_resposnes(new SubmissionResouce($result));
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

        if (!$request->has("file")) {
            return $this->fiald_resposnes("file_not_found");
        } else {
            $assingment = Assingment::find($id);
            $strogePath = $this->uploadeFile($request->file("file"), $assingment->title);
            if (is_null($strogePath)) {
                return $this->fiald_resposnes("path_not_storge");
            } else {
                $request["submissions_date"] = Carbon::now();
                $request["state"] = $request->submissions_date->lte($assingment->deadline);
                $request["path"] = $strogePath;
                $submission = $this->checkFile($request);
                if (!is_null($submission)) {
                    $this->deleteFile($submission->path);
                    $submission = $submission->update($request->except("file"));
                    return  $this->success_resposnes($submission, message: "updated");
                }
                $submission = Submission::create($request->except("file"));
                if (is_null($submission)) {
                    return $this->fiald_resposnes();
                }

                return  $this->success_resposnes($submission);
            }
        }
    }

    protected function checkFile(Request $request)
    {
        $submission = Submission::whereStudentId($request->student_id)->whereAssingmentId($request->assingment_id)->first();
        if (is_null($submission)) return null;
        return $submission;
    }

    protected function deleteFile($path)
    {
        unlink(storage_path() . "/app/public/" . explode("storage/", $path)[1]);
        // unlink(storage_path() . "/app/public/files/assingments/ddf/1704441865file.php");
        // Storage::delete($path);
    }

    protected function uploadeFile($file, $assingmentName)
    {
        $file_name = time() . 'file.' . $file->getClientOriginalExtension();
        $path = 'public/files/assingments/' . $assingmentName;
        $stored_path = $file->storeAs($path, $file_name);

        return $stored_path;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $submissionId)
    {
        $validtion = $this->rules($request);

        if ($validtion->fails()) {
            return $this->fiald_resposnes(result: $validtion->errors(), code: 300);
        }

        $submission = Submission::with("assingment")->find($submissionId);
        if ($submission->assingment->grade < $request->grade) {
            return $this->fiald_resposnes(message: "grade is grater then the grade that assing to this assingment");
        }
        $assingment_id= $submission->assingment->enrollment_id;
        $submission = tap($submission->update($request->only('grade')));
        if (is_null($submission)) {
            return $this->fiald_resposnes("file_not_found");
        }
        $request["fianl_mark"] = $this->sumAllGrades(Student::find($request->student_id)->submissins);
        $grade = Grade::where("student_id",$request->student_id)->where("enrollment_id",$assingment_id) ;
        $grade = tap($grade->update($request->only("mark"))); //update the mark of the student
        if (!$grade) {
            return $this->fiald_resposnes("mark_not_update" . $grade);
        }

        return $this->success_resposnes($grade);
    }

    public function sumAllGrades($result)
    {
        $total = 0;
        foreach ($result as $item) {

            $total += $item->grade;
        }

        return $total;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
    }

    public function rules(Request $request)
    {
        $update = Route::currentRouteName() == 'update';


        return Validator::make($request->all(), [
            "student_id" =>  ['required', "exists:students,id"],
            "assingment_id" => $update ? ['required', "exists:assingments,id"] : "",
            "grade" => $update ?  ["numeric", "max:100", "min:0"] : "",
            "file" => $update ? "" : ['required', "mimes:jpg,jpeg,png,pdf,xls,doc,docm,docx,dot,pptx,rar,zip,txt"],
        ]);
    }
}
