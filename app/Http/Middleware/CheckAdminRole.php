<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Gère la requête entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérification de si l'utilisateur est connecté et s'il a le rôle d'admin
        if (! $request->user() || $request->user()->role !== 'admin') {
            
            // 2. Si non, on bloque l'accès
            return response()->json([
                'message' => 'Accès refusé. Vous devez être administrateur pour effectuer cette action.'
            ], 403);
        }

        // 3. Si oui, accès autorisé
        return $next($request);
    }
}