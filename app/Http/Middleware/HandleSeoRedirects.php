<?php

namespace App\Http\Middleware;

use App\Models\SeoRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSeoRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . ltrim($request->path(), '/');

        $redirect = SeoRedirect::where(function ($q) use ($path) {
            $q->where('source_path', $path)
              ->orWhere('source_path', ltrim($path, '/'));
        })
        ->where('is_active', true)
        ->first();

        if ($redirect) {
            $redirect->incrementQuietly('hit_count');
            return redirect($redirect->destination_url, (int) $redirect->status_code);
        }

        return $next($request);
    }
}
