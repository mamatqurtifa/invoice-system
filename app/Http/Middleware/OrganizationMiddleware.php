<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'organization') {
            abort(403, 'Unauthorized access. Organization only.');
        }

        // Check if user has an organization profile
        if (!$user->organization) {
            return redirect()->route('organization.profile.edit')
                ->with('error', 'You need to complete your organization profile first.');
        }

        return $next($request);
    }
}