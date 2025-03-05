<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TeacherControll;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoleController;

// Rotas para UserController
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Rotas para SchoolController
Route::get('/schools', [SchoolController::class, 'index']);
Route::post('/schools', [SchoolController::class, 'store']);
Route::get('/schools/{id}', [SchoolController::class, 'show']);
Route::put('/schools/{id}', [SchoolController::class, 'update']);
Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);

// Rotas para TeacherControll
Route::get('/teachers', [TeacherControll::class, 'index']);
Route::post('/teachers', [TeacherControll::class, 'store']);
Route::get('/teachers/{id}', [TeacherControll::class, 'show']);
Route::put('/teachers/{id}', [TeacherControll::class, 'update']);
Route::delete('/teachers/{id}', [TeacherControll::class, 'destroy']);

// Rotas para SubjectController
Route::get('/subjects', [SubjectController::class, 'index']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects/{id}', [SubjectController::class, 'show']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']);
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

// Rotas para AppointmentController
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

// Rotas para AttendanceController
Route::get('/attendances', [AttendanceController::class, 'index']);
Route::post('/attendances', [AttendanceController::class, 'store']);
Route::get('/attendances/{id}', [AttendanceController::class, 'show']);
Route::put('/attendances/{id}', [AttendanceController::class, 'update']);
Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy']);

// Rotas para StudentSubjectController
Route::get('/student-subjects', [StudentSubjectController::class, 'index']);
Route::post('/student-subjects', [StudentSubjectController::class, 'store']);
Route::get('/student-subjects/{id}', [StudentSubjectController::class, 'show']);
Route::put('/student-subjects/{id}', [StudentSubjectController::class, 'update']);
Route::delete('/student-subjects/{id}', [StudentSubjectController::class, 'destroy']);

// Rotas para ClassroomController
Route::get('/classrooms', [ClassroomController::class, 'index']);
Route::post('/classrooms', [ClassroomController::class, 'store']);
Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);
Route::put('/classrooms/{id}', [ClassroomController::class, 'update']);
Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy']);

// Rotas para EnrollmentController
Route::get('/enrollments', [EnrollmentController::class, 'index']);
Route::post('/enrollments', [EnrollmentController::class, 'store']);
Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']);
Route::put('/enrollments/{id}', [EnrollmentController::class, 'update']);
Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy']);

// Rotas para GradeController
Route::get('/grades', [GradeController::class, 'index']);
Route::post('/grades', [GradeController::class, 'store']);
Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::put('/grades/{id}', [GradeController::class, 'update']);
Route::delete('/grades/{id}', [GradeController::class, 'destroy']);

// Rotas para RoleController
Route::apiResource('roles', RoleController::class);

// Testar conexão com o banco de dados
Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['success' => true, 'message' => 'Conexão com o banco de dados estabelecida com sucesso!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()], 500);
    }
});
