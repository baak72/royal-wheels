<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Retourne la liste de tous les utilisateurs (Clients et Employés)
     */
    public function index()
    {
        // On récupère tous les utilisateurs, du plus récent au plus ancien
        $users = User::orderBy('created_at', 'desc')->get();

        return response()->json($users, 200);
    }

    /**
     * Active ou désactive le compte d'un utilisateur
     */
    public function toggleActiveStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Sécurité : Un administrateur ne peut pas se bloquer lui-même
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Action refusée : Vous ne pouvez pas désactiver votre propre compte Administrateur.'
            ], 403);
        }

        // On inverse le statut
        $user->is_active = !$user->is_active;
        $user->save();

        // Un mot clair pour le message de retour
        $statusWord = $user->is_active ? 'réactivé' : 'désactivé';

        return response()->json([
            'message' => "Le compte de {$user->first_name} {$user->last_name} a été {$statusWord} avec succès.",
            'user' => $user
        ], 200);
    }

    /**
     * Supprime définitivement un employé
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Règle métier : On empêche la suppression des clients ou des admins
        if ($user->role !== 'employee') {
            return response()->json([
                'message' => 'Action refusée : Vous ne pouvez supprimer que les comptes employés.'
            ], 403);
        }

        // 2. Sécurité supplémentaire : Un administrateur ne peut pas supprimer son propre compte
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'Action refusée : Vous ne pouvez pas supprimer votre propre compte.'
            ], 403);
        }

        // 3. Suppression
        $user->delete();

        return response()->json([
            'message' => "Le compte employé de {$user->first_name} {$user->last_name} a été supprimé définitivement."
        ], 200);
    }

    /**
     * Crée un nouveau compte Admin ou Employé
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8',
            'role'       => 'required|string|in:employee,admin', // Interdiction de créer des comptes clients via cette route
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => bcrypt($validated['password']),
            'role'       => $validated['role'],
            'is_active'  => true,
        ]);

        return response()->json([
            'message' => "Le compte {$user->role} de {$user->first_name} a été créé avec succès.",
            'user' => $user
        ], 201);
    }
}