<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use App\Http\Requests\StoreTransaccionRequest;

class TransaccionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estado = $request->query('estado');
        $transacciones = Transaccion::whereHas('comercio', fn($q) =>
        $q->where('user_id', $request->user()->id))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->latest()
            ->get();

        return TransaccionResource::collection($transacciones);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent(); // Código 204
    }

    private function autorizar(Transaccion $t): void
    {
        abort_unless($t->comercio && $t->comercio->user_id === request()->user()->id, 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaccionRequest $request)
    {
        $transaccion = Transaccion::create($request->validated());
        return (new TransaccionResource($transaccion))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);
        return new TransaccionResource($transaccion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaccion $transaccion)
    {
        $this->autorizar($transaccion);

        $datos = $request->validate([
            'comercio_id' => ['sometimes', 'exists:comercios,id'],
            'cliente_nombre' => ['sometimes', 'string', 'max:255'],
            'monto' => ['sometimes', 'numeric'],
            'estado' => ['sometimes', 'string'],
        ]);

        $transaccion->update($datos);

        return new TransaccionResource($transaccion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);
        $transaccion->delete();
        return response()->noContent(); // 204
    }
}
