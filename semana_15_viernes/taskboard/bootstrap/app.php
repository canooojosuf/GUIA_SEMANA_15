<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
   ->withExceptions(function (Exceptions $exceptions): void {
        
        // 1. Arregla el CL-14 (Peticiones a la API siempre en JSON)
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $e) => $request->is('api/*') || $request->expectsJson(),
        );

        // 2. Arregla la fuga de información en errores de Base de Datos (Lab 4)
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'No se pudo guardar la información. Intenta de nuevo más tarde.',
                ], 500);
            }
        });

    })->create();
