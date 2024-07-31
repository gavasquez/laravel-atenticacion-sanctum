<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function (){

    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);

    Route::get('employeesall', [EmployeeController::class, 'all']);
    Route::get('employeebydepartment', [EmployeeController::class, 'employeesByDepartament']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

});

