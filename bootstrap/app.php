<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Redirect jika user belum login
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Redirect jika user sudah login tapi mencoba akses halaman guest (/login)
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            if ($user?->role === 'admin') {
                return route('admin.dashboard');
            } elseif ($user?->role === 'guru') {
                return route('guru.dashboard');
            } elseif ($user?->role === 'siswa') {
                return route('siswa.dashboard');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();