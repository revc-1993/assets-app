<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;

class DepartmentController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * Listar todos los departamentos
     */
    public function index()
    {
        $departments = Department::all();

        if (!$departments) {
            return $this->errorResponse(null, 'Error de departamentos', 404);
        }

        if ($departments->isEmpty()) {
            return $this->successResponse($departments, 'No hay departamentos disponibles');
        }

        return $this->successResponse($departments, 'Lista de departamentos obtenida correctamente');
    }

    /**
     * Mostrar un departamento específico
     */
    public function show(int $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return $this->errorResponse(null, 'Departamento no encontrado', 404);
        }

        return $this->successResponse($department, 'Departamento encontrado');
    }

    /**
     * Crear un nuevo departamento
     */
    public function store(DepartmentStoreRequest $request)
    {
        $department = Department::create($request->validated());

        return $this->successResponse($department, 'Departamento creado correctamente', 201);
    }

    /**
     * Actualizar un departamento
     */
    public function update(DepartmentUpdateRequest $request, int $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return $this->errorResponse(null, 'Departamento no encontrado', 404);
        }

        $department->update($request->validated());

        return $this->successResponse($department, 'Departamento actualizado correctamente');
    }

    /**
     * Eliminar un departamento
     */
    public function destroy(int $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return $this->errorResponse(null, 'Departamento no encontrado', 404);
        }

        $department->delete();

        return $this->successResponse(null, 'Departamento eliminado correctamente');
    }
}
