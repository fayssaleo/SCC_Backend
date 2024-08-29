<?php

namespace App\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = Users::all();

        return [
            'payload' => $users,
            'status' => 200,
        ];
    }

    public function login(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            "email" => "required|string",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => "Validation error",
                "details" => $validator->errors()
            ], 400);
        }

        // Récupération de l'utilisateur
        $user = Users::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "error" => "Email not found",
                "status" => 404,
            ], 404);
        }

        // Vérification du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "error" => "Incorrect password",
                "status" => 401,
            ], 401);
        }

        // Création du token
        $token = $user->createToken($user->username)->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        
        return response()->json([
            "payload" => $response,
            "status" => 200
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $user = Users::create([
            'username' => $request->username,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            "payload" => $user,
            "status" => 200
        ]);
    }

    public function get($id)
    {
        return Users::findOrFail($id);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string',
            'FirstName' => 'string',
            'LastName' => 'string',
            'email' => 'email|unique:users,email,' . $request->id,
            'password' => 'string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $user = Users::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                "error" => "User not found",
                "status" => 404,
            ], 404);
        }
        $user->update([
            'username' => $request->username,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 200);
    }


    public function delete(Request $request)
    {
        $user = Users::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                "error" => "User not found",
                "status" => 404,
            ], 404);
        }
        $user->delete();
        

        return response()->json(['message' => 'User deleted'], 200);
    }public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Supprime tous les tokens de l'utilisateur
    
        return response()->json(['message' => 'Déconnexion réussie.'], 200);
    }
    
}
