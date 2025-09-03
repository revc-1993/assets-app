<?php

namespace App\Http\Api\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    // Listar todos los empleados
    public function index()
    {
        return response()->json(Employee::all());
    }

    // Mostrar un empleado específico
    public function show(int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }
        return response()->json($employee);
    }

    // Crear un nuevo empleado
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'dni' => 'required|string|max:20|unique:employees,dni',
            'email' => 'nullable|email|max:100',
            // Agrega aquí otras validaciones según tu modelo
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    // Actualizar un empleado existente
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        $validated = $request->validate([
            'first_names' => 'sometimes|required|string|max:100',
            'last_names' => 'sometimes|required|string|max:100',
            'dni' => 'sometimes|required|string|max:20|unique:employees,dni,' . $employee->id,
            'email' => 'nullable|email|max:100',
            // Agrega aquí otras validaciones según tu modelo
        ]);

        $employee->update($validated);
        return response()->json($employee);
    }

    // Eliminar un empleado
    public function destroy(int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        $employee->delete();
        return response()->json(['message' => 'Empleado eliminado']);
    }
}
