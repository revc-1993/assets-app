<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;

class AssetController extends Controller
{
    // Listar todos los bienes
    public function index()
    {
        return response()->json(Asset::all());
    }

    // Mostrar un bien específico
    public function show(int $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }
        return response()->json($asset);
    }

    // Crear un nuevo bien
    public function store(AssetRequest $request)
    {
        $asset = Asset::create($request->validated());
        return response()->json($asset, 201);
    }

    // Actualizar un bien existente
    public function update(Request $request, int $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }

        $asset->update($request->validated());
        return response()->json($asset);
    }

    // Eliminar un bien
    public function destroy(int $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }

        $asset->delete();
        return response()->json(['message' => 'Bien eliminado']);
    }
}
