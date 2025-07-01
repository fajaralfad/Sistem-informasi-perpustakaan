<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Pastikan user memiliki role
        if (!isset($user->role)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role tidak ditemukan');
        }

        // Cek apakah role sesuai
        if ($user->role !== $role) {
            // Redirect ke dashboard yang sesuai role-nya
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'anggota') { // UBAH: member -> anggota
                return redirect('/member/dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak valid');
            }
        }

        return $next($request);
    }
}

// Jangan lupa untuk register middleware ini di Kernel.php
// protected $middlewareAliases = [
//     'role' => \App\Http\Middleware\CheckRole::class,
// ];