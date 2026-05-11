<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Ensure user is authenticated and has a CMS role.
     * Also adds X-Robots-Tag header to prevent search engine indexing of admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has any CMS role
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor', 'author'])) {
            abort(403, 'You do not have access to the admin panel.');
        }

        $response = $next($request);

        // Prevent search engine indexing of admin panel (PRD §9)
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');

        return $response;
    }
}
