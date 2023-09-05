<?php

namespace App\Http\Middleware;

use App\Models\VoteType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionIsClosed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session->status_id === VoteType::SECRET) {
            return back()->with('error', 'La séance ' . $request->session->title . ' est définitivement fermée');
        }

        return $next($request);
    }
}
