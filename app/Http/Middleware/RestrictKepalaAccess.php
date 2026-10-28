<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictKepalaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'kepala_sppg') {
            $routeName = $request->route() ? $request->route()->getName() : null;
            $userSppgId = Auth::user()->sppg_id;

            if ($routeName === 'user.sppg.show') {
                $id = $request->route('id');
                if ($userSppgId && (string)$id === (string)$userSppgId) {
                    return $next($request);
                }
                return redirect()
                    ->route('kepala.dashboard')
                    ->with('error', 'Anda hanya dapat melihat detail SPPG milik unit Anda sendiri.');
            }

            if ($routeName === 'user.sasaran.index') {
                $id = $request->route('id');
                if ($userSppgId && (string)$id === (string)$userSppgId) {
                    return $next($request);
                }
                return redirect()
                    ->route('kepala.dashboard')
                    ->with('error', 'Anda hanya dapat melihat sasaran SPPG milik unit Anda sendiri.');
            }

            // Redirect any other public route to Kepala SPPG dashboard
            return redirect()->route('kepala.dashboard');
        }

        return $next($request);
    }
}
