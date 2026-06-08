<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Only enforce for Exporter, Buyer, and Logistics
        if ($user->hasRole('exporter')) {
            $profile = \App\Models\ExporterProfile::where('user_id', $user->id)->first();
            if (!$profile || in_array($profile->verification_status, ['pending', 'rejected'])) {
                return redirect()->route('kyc.onboarding');
            }
        } elseif ($user->hasRole('buyer')) {
            $profile = \App\Models\BuyerProfile::where('user_id', $user->id)->first();
            if (!$profile || in_array($profile->verification_status, ['pending', 'rejected'])) {
                return redirect()->route('kyc.onboarding');
            }
        } elseif ($user->hasRole('logistics')) {
            $profile = \App\Models\LogisticsProfile::where('user_id', $user->id)->first();
            if (!$profile || in_array($profile->verification_status, ['pending', 'rejected'])) {
                return redirect()->route('kyc.onboarding');
            }
        }

        return $next($request);
    }
}
