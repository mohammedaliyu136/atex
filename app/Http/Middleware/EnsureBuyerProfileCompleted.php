<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBuyerProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            // Check if they are already on the profile page or logging out to avoid redirect loop
            if ($request->routeIs('buyer.profile.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            $profile = auth()->user()->buyerProfile;

            // Profile is incomplete if it doesn't exist or is missing required addresses
            $isComplete = $profile && 
                          !empty($profile->shipping_address) && 
                          !empty($profile->billing_address);

            if (!$isComplete) {
                return redirect()->route('buyer.profile.show')
                    ->with('warning', 'Please complete your billing and shipping information to continue using your buyer account.');
            }
        }

        return $next($request);
    }
}
