<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Mendukung dua mode:
     * 1. checkRole:superadmin,kepala gudang  → cek nama role (lama)
     * 2. checkRole:permission:barang         → cek permission menu_key
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        // Belum login atau tidak punya role
        if (!$user || !$user->role) {
            abort(403, 'Unauthorized action.');
        }

        // Mode permission: checkRole:permission:menu_key
        if (count($roles) === 1 && str_starts_with($roles[0], 'permission:')) {
            $menuKey = substr($roles[0], strlen('permission:'));

            // Superadmin selalu lolos
            if ($user->isSuperAdmin()) {
                return $next($request);
            }

            if ($user->canViewMenu($menuKey)) {
                return $next($request);
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Mode lama: cek nama role
        if (in_array($user->role->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
