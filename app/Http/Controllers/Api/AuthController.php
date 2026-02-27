<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Inscrit un nouvel utilisateur (Client) et lui donne son premier Token.
     */
    public function register(Request $request)
    {
        // 1. Vérification que le formulaire Vue.js envoie bien les bonnes données
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        // 2. Création du client dans la base de données
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            // Cryptage du mot de passe avec Hash::make de manière irréversible.
            'password' => Hash::make($validatedData['password']),
            'role' => 'client', // Par défaut, tout nouvel inscrit est un client
        ]);

        // 3. Utilisation de Sanctum pour créer le jeton d'accès
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Renvoie un JSON de succès au Front-end avec le badge
        return response()->json([
            'message' => 'Inscription réussie avec succès.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201); // "Créé avec succès"
    }

    /**
     * Connecte un utilisateur existant et lui délivre un nouveau Token.
     */
    public function login(Request $request)
    {
        // 1. Vérification que le client a bien rempli l'email et le mot de passe
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Recherche de l'utilisateur dans la base de données avec cet email
        $user = User::where('email', $request->email)->first();

        // 3. Vérification que l'utilisateur existe et que le mot de passe est correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Si non, renvoie une erreur
            return response()->json([
                'message' => 'Les identifiants sont incorrects.'
            ], 401);
        }

        // 4. Si validé, on lui génère un nouveau Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Renvoie des infos et du Token au Front-end
        return response()->json([
            'message' => 'Connexion réussie avec succès.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200); // 200 = OK
    }

    /**
     * Déconnecte l'utilisateur (Révocation du Token).
     */
    public function logout(Request $request)
    {
        // Récupération et suppression du token de l'utilisateur connecté
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie. Le token a été détruit.'
        ], 200);
    }
}