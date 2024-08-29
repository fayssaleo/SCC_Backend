<?php

namespace App\Modules\Consommateurs\Http\Controllers;

use App\Modules\Consommateurs\Models\Consommateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ConsommateursController
{

    public function index()
    {
        return Consommateurs::all();
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:consommateurs,username',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'shift' => 'required|string',
            'fonction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $consommateur = Consommateurs::create([
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'shift' => $request->shift,
            'fonction' => $request->fonction,
        ]);

        return response()->json($consommateur, 201);
    }



    public function get($id)
    {
        $consommateur = Consommateurs::findOrFail($id);
        return response()->json($consommateur);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string|unique:consommateurs,username,' . $id,
            'firstname' => 'string',
            'lastname' => 'string',

            'shift' => 'string',
            'fonction' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $consommateur = Consommateurs::findOrFail($id);
        $consommateur->update([
            'username' => $request->username ?? $consommateur->username,
            'firstname' => $request->firstname ?? $consommateur->firstname,
            'lastname' => $request->lastname ?? $consommateur->lastname,

            'shift' => $request->shift ?? $consommateur->shift,
            'fonction' => $request->fonction ?? $consommateur->fonction,
        ]);

        return response()->json($consommateur, 200);
    }


    public function delete($id)
    {
        $consommateur = Consommateurs::findOrFail($id);
        $consommateur->delete();

        return response()->json(['message' => 'Consommateur deleted'], 200);
    }

    public function store(Request $request)
    {
        // Valider les données globales
        $validator = Validator::make($request->all(), [
            'consumers' => 'required|array',
            'consumers.*.username' => 'required|string',
            'consumers.*.firstname' => 'nullable|string',
            'consumers.*.lastname' => 'nullable|string',
            'consumers.*.shift' => 'nullable|string',
            'consumers.*.fonction' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        // Récupérer tous les usernames existants
        $existingUsernames = Consommateurs::pluck('username')->toArray();

        // Filtrer les consommateurs à ajouter (ignorer ceux qui existent déjà)
        $consumersToAdd = array_filter($request->input('consumers'), function ($consumer) use ($existingUsernames) {
            return !in_array($consumer['username'], $existingUsernames);
        });

        // Ajouter les consommateurs filtrés
        $addedCount = 0;
        foreach ($consumersToAdd as $consumerData) {
            Consommateurs::create([
                'username' => $consumerData['username'],
                'firstname' => $consumerData['firstname'] ?? null,
                'lastname' => $consumerData['lastname'] ?? null,
                'shift' => $consumerData['shift'] ?? null,
                'fonction' => $consumerData['fonction'] ?? null,
            ]);
            $addedCount++;
        }

        return response()->json([
            'message' => $addedCount > 0 ? 'Consommateurs ajoutés avec succès' : 'Aucun nouveau consommateur à ajouter',
            'added_count' => $addedCount
        ], $addedCount > 0 ? 201 : 200);
    }
    public function getIdsByUsernames(Request $request)
    {
        // Validation des usernames
        $validator = Validator::make($request->all(), [
            'usernames' => 'required|array',
            'usernames.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $usernames = $request->input('usernames');

        // Obtenir les IDs des consommateurs
        $consommateurs = Consommateurs::whereIn('username', $usernames)->get(['username', 'id']);
        $ids = $consommateurs->pluck('id', 'username')->toArray();

        return response()->json($ids, 200);
    }
    public function getConsommateurIdByUsername($username)
    {
        $consommateur = Consommateurs::where('username', $username)->first();
        return $consommateur ? $consommateur->id : null;
    }

}
