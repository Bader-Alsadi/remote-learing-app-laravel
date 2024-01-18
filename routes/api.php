<?php

use App\Http\Controllers\AssingmentController;
use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\AuthContollerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentDetileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
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
use Illuminate\Support\Facades\Artisan;

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


Route::get('srotge', function () {
    Artisan::call('storage:link');
});

Route::get('cach', function () {
    Artisan::call('route:cache');

});
Route::middleware('localistion')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get("logout", [AuthContoller::class, "logout"]);
        Route::get("instructor-info/{id}", [UserController::class, "instructorInfo"]);
        Route::get("students/{id}", [StudentController::class, "studentSubjects"]);
        Route::get("students_courses/{id}", [EnrollmentController::class, "enrollmentStudents"]);
        Route::post("updategrade/{id}", [SubmissionController::class, "update"])->name("update");
        Route::post("updatelecturer/{id}", [LecturerController::class, "update"])->name("updateLecturer");
        Route::post("updateAssingment/{id}", [AssingmentController::class, "update"])->name("updateAssingment");
        Route::post("updateSubject/{id}", [SubjectController::class, "update"])->name("updateSubject");
        Route::post("updategrade", [GradeController::class, "updateGrade"])->name("updateGrade");
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
            "students.grades" => GradeController::class,
        ]);
    });
    Route::post("login", [AuthContoller::class, "login"]);
});
