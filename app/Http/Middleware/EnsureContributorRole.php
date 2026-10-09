<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureContributorRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->is_active !== null && !$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account deactivated.'], 403);
            }
            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if (!$user->isContributor()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Contributor accreditation required.'], 403);
            }
            return redirect()->route('contributor.apply')->with('info', 'Please submit an application to become an accredited AQ NEWSWIRE contributor.');
        }

        return $next($request);
    }
}
