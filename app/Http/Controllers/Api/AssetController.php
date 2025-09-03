<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'esbye_code' => 'required|integer|unique:assets,esbye_code',
            'description' => 'required|string|max:200',
            'serie' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:70',
            'condition' => 'nullable|string|max:40',
            'book_value' => 'nullable|numeric',
            'employee_id' => 'nullable|exists:employees,id',
            'department_id' => 'required|exists:departments,id',
            'inactive' => 'boolean',
            'registered_esbye' => 'boolean',
            'comments' => 'nullable|string',
            'origin' => 'nullable|string',
            // Agrega otras validaciones según tu modelo
        ]);

        $asset = Asset::create($validated);
        return response()->json($asset, 201);
    }

    // Actualizar un bien existente
    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }

        $validated = $request->validate([
            'esbye_code' => 'sometimes|required|integer|unique:assets,esbye_code,' . $asset->id,
            'description' => 'sometimes|required|string|max:200',
            'serie' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:70',
            'condition' => 'nullable|string|max:40',
            'book_value' => 'nullable|numeric',
            'employee_id' => 'nullable|exists:employees,id',
            'department_id' => 'sometimes|required|exists:departments,id',
            'inactive' => 'boolean',
            'registered_esbye' => 'boolean',
            'comments' => 'nullable|string',
            'origin' => 'nullable|string',
            // Agrega otras validaciones según tu modelo
        ]);

        $asset->update($validated);
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
