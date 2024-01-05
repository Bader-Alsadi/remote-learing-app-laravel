<?php

use App\Http\Controllers\AssingmentController;
use App\Http\Controllers\AuthContollerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentDetileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use App\Models\Department;
use App\Models\DepartmentDetile;
use App\Models\Enrollment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('localistion')->group(function () {
    Route::middleware('auth:sanctum')->group( function () {
        Route::get("logout", [AuthContollerController::class, "logout"]);
        Route::get("instructor-info/{id}", [UserController::class, "instructorInfo"]);

        Route::apiResources([
            "users" => UserController::class,
            "departments" => DepartmentController::class,
            "departmentDetiles" => DepartmentDetileController::class,
            "enrollments" => EnrollmentController::class,
            "subjects" => SubjectController::class,
            "lecturers.materials" => MaterialController::class,
            "enrollments.lecturers" => LecturerController::class,
            "enrollments.assingments" => AssingmentController::class,
            "assingments.submissions" => SubmissionController::class,
            "departmentDetiles.students" => StudentController::class,
        ]);
    });
    Route::post("login", [AuthContollerController::class, "login"]);
});
