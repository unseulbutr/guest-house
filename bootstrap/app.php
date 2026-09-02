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
        // Alias middleware dari spatie/laravel-permission.
        // Wajib didaftarkan manual di Laravel 11/12 (struktur baru tanpa Kernel.php),
        // kalau tidak, middleware 'role:...' di routes/web.php akan error
        // "Target class [role] does not exist."
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // Cek akun tidak dinonaktifkan Super Admin (fitur Manajemen User).
            // Kalau user->is_active == false, otomatis logout + redirect ke login.
            'active' => \App\Http\Middleware\EnsureAccountIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();