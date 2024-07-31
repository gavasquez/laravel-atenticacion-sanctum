<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Validator;
use DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $employees = Employee::select('employees.*', 'departments.name as department')->join('departments', 'departments.id', '=', 'employees.departament_id')->paginate(10);
            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $employees,
            ], 200);

        } catch (Exception $th) {
            return response()->json([
                'status'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->input(), [
                'name' => 'required|string|min:1|max:100',
                'email' => 'required|email|max:80',
                'phone' => 'required|max:15',
                'departament_id' => 'required|numeric',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validación',
                    'data' => $validator->errors()->all(),
                ], 400);
            }

            $employee = new Employee($request->input());

            $employee->save();
            return response()->json([
                'status' => true,
                'message' => 'Empleado creado con éxito',
                'data' => $employee,
            ], 201);

        } catch (Exception $th) {
            return response()->json([
                'status'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json([
            'status' => true,
            'data' => $employee,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {

            $validator = Validator::make($request->input(), [
                'name' => 'required|string|min:1|max:100',
                'email' => 'required|email|max:80',
                'phone' => 'required|max:15',
                'departament_id' => 'required|numeric',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validación',
                    'data' => $validator->errors()->all(),
                ], 400);
            }

            $employee->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully',
                'data' => $employee,
            ], 200);

        } catch (Exception $th) {
            return response()->json([
                'status'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response()->json([
                'status' => true,
                'message' => 'Employee deleted successfully', // return success message
                'data' => $employee,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }

    public function employeesByDepartament(){

        try {

            $employees = Employee::select(DB::raw('count(employees.id) as count, departments.name'))
                ->join('departments', 'departments.id', '=', 'employees.departament_id')
                ->groupBy('departments.name')->get();
            return response()->json([
                'status' => true,
                'message' => 'List of employees by department',
                'data' => $employees,
            ], 200);

        } catch (Exception $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }

    }

    public function all(){

        try {
            $employees = Employee::select(DB::raw('employees.*, departments.name as departaments'))
                ->join('departments', 'departments.id', '=', 'employees.departament_id')->get();
            return response()->json([
                'status' => true,
                'message' => 'List of employees by department',
                'data' => $employees,
            ], 200);

        } catch (Exception $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }

    }
}
