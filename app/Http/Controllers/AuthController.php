<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function create(Request $request){

        $validator = Validator::make($request->input(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Error de validación.',
                'data'=> $validator->errors()->all(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encripta la contraseña
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado.',
            'data'  => $user,
            'token' => $user->createToken('API_TOKEN')->plainTextToken
        ], 200);
    }

    public function login(Request $request){

        $validator = Validator::make($request->input(), [
            'email' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Error de validación.',
                'data'=> $validator->errors()->all(),
            ], 400);
        }

        if(!Auth::attempt($request->only('email', 'password'))){ // Verifica si el usuario existe y la contraseña es correcta
            return response()->json([
                'status' => false,
                'message' => 'Error de validación.',
                'data'=> ['Unauthorized'],
            ], 401);
        }

        $user = User::where('email', $request->email)->first(); // Obtiene el usuario que hizo el login

        return response()->json([
            'status' => true,
            'message' => 'Usuario logged in successfully.',
            'data'  => $user,
            'token' => $user->createToken('API_TOKEN')->plainTextToken
        ], 200);

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Usuario logged out successfully.',
        ], 200);
    }
}
