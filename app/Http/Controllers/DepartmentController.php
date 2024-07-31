<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;

class DepartmentController extends Controller
{

    public function index()
    {
        $departments = Department::all();
        return response()->json([
            'status' => true,
            'message' => 'Lists of departments',
            'data'=> $departments,
        ], 200); // return departments as json
    }


    public function store(Request $request)
    {

        try {

            $rules = ['name' => 'required|string|min:1|max:100'];
            $validator = Validator::make($request->input(), $rules);
            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Errores de validación',
                    'data' => $validator->errors()->all(),
                ], 400);
            }
            $department = new Department($request->input());
            $department->save();
            return response()->json([
                'status' => true,
                'message' => 'Department created successfully', // return success message
                'data' => $department,
            ], 201);

        } catch (Exception $th) {
            return response()->json([
                'status'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }


    public function show(string $id)
    {
        try {
            $department = Department::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Lists of departments',
                'data' => $department,
            ], 200);

        } catch (ModelNotFoundException $th) {
            return response()->json([
                'status' => false,
                'message' => 'Departament not found',
                'data' => $id,
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        try {

            $rules = ['name' => 'required|string|min:1|max:100'];
            $validator = Validator::make($request->input(), $rules);
            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Errores de validación',
                    'data' => $validator->errors()->all(),
                ], 400);
            }
            $department->update($request->input()); // update the department

            return response()->json([
                'status' => true,
                'message' => 'Department updated successfully', // return success message
                'data' => $department,
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
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return response()->json([
                'status' => true,
                'message' => 'Department deleted successfully', // return success message
                'data' => $department,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Error inesperado',
                'data'=> $th->getMessage(),
            ], 500);
        }
    }
}
