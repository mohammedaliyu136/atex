<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceDocumentAcceptance
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            if ($request->routeIs('legal-acceptance.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            if (!\Illuminate\Support\Facades\Auth::user()->hasAcceptedLatestLegalDocuments()) {
                return redirect()->route('legal-acceptance.show');
            }
        }

        return $next($request);
    }
}
