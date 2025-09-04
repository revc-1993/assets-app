<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Services\AssetService;
use App\Http\Requests\AssetRequest;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Busca un bien por su código ESBYE o número de serie.
     *
     * @param string $searchTerm
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $searchTerm)
    {
        $asset = $this->assetService->findAssetByCodeOrSerie($searchTerm);

        if (!$asset) {
            return response()->json(['message' => 'Bien no encontrado'], 404);
        }

        return response()->json($asset);
    }

    // *************** CRUD BÁSICO PARA BIENES ***************

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
}
