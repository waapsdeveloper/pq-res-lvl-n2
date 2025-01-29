<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtractRestaurantId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Restaurant ID ko request se extract karein
        $restaurantId = $request->header('restaurant_id') ?? $request->query('restaurant_id');

        if (!$restaurantId) {
            return response()->json(['error' => 'Restaurant ID is required'], 400);
        }

        // Restaurant ID ko request object me inject kar dein
        $request->merge(['restaurant_id' => $restaurantId]);

        return $next($request);
    }
}
