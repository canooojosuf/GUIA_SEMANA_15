<?php

use App\Models\Comercio;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ComercioResource;
use App\Http\Controllers\Api\TransaccionApiController;
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::post('/login', [AuthApiController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/yo', [AuthApiController::class, 'yo']);
    Route::post('/logout', [AuthApiController::class, 'logout']);



/*Route::get('/prueba-error-bd', function () {
    return DB::table('tabla_que_no_existe')->get();
});*/

    /*Route::get('/comercios', function () {
        return Comercio::all();
    });

    Route::get('/comercios', function () {
        return ComercioResource::collection(Comercio::all());
    });*/

    /* Route::get('/comercios/{comercio}', function (Comercio $comercio) {
        return new ComercioResource($comercio->load('transacciones'));
    });*/

    Route::get('/comercios', function (Request $request) {
        return ComercioResource::collection(
            $request->user()->comercios()->orderBy('nombre_comercio')->get()
        );
    });
    Route::get('/comercios/{comercio}', function (Request $request, Comercio $comercio) {
        abort_unless($comercio->user_id === $request->user()->id, 404);
        return new ComercioResource($comercio->load('transacciones'));
    });

    Route::get('/tokens', [AuthApiController::class, 'listarTokens']);

    Route::delete('/tokens/{id}', function (Request $request, $id) {
        $token = $request->user()->tokens()->where('id', $id)->firstOrFail();
        $token->delete();
        return response()->noContent();
    });


    Route::apiResource('transacciones', TransaccionApiController::class);
});

/*Route::get('/comercios', function () {
    return Comercio::all();
});

Route::get('/comercios', function () {
    return ComercioResource::collection(Comercio::all());
});

Route::get('/comercios/{comercio}', function (Comercio $comercio) {
    return new ComercioResource($comercio->load('transacciones'));
});


Route::apiResource('transacciones', TransaccionApiController::class);*/
