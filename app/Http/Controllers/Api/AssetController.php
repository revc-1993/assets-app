<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use App\Services\AssetService;
use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    use \App\Traits\ApiResponse;

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
            return $this->errorResponse(null, 'Bien no encontrado', 404);
        }

        return $this->successResponse($asset, 'Bien encontrado correctamente');
    }

    public function updateEsbyeState(int $id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            return $this->errorResponse(null, 'Bien no encontrado', 404);
        }

        $asset = $this->assetService->updateEsbyeRegistration($asset);

        return $this->successResponse($asset, 'Bien actualizado correctamente');
    }

    // *************** CRUD BÁSICO PARA BIENES ***************

    // Listar todos los bienes
    public function index()
    {
        $assets = Asset::all();

        if (!$assets) {
            return $this->errorResponse(null, 'Error de bienes', 404);
        }

        if ($assets->isEmpty()) {
            return $this->successResponse($assets, 'No hay bienes disponibles');
        }

        return $this->successResponse($assets, 'Lista de bienes obtenida correctamente');
    }

    // Mostrar un bien específico
    public function show(int $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return $this->errorResponse(null, 'Bien no encontrado', 404);
        }

        return $this->successResponse($asset, 'Bien encontrado');
    }
}
