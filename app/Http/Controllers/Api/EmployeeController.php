<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\EmployeeService;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * @var EmployeeService
     */
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Busca un empleado por cédula o nombre.
     *
     * @param string $searchTerm
     * @return JsonResponse
     */
    public function search(string $searchTerm)
    {
        $employee = $this->employeeService->findEmployeeByDniOrName($searchTerm);

        if (!$employee) {
            return $this->errorResponse(null, 'Empleado no encontrado.', 404);
        }

        return $this->successResponse($employee, 'Empleado encontrado');
    }

    // *************** CRUD BÁSICO PARA EMPLEADOS ***************

    // Listar todos los empleados
    public function index()
    {
        $employees = Employee::all();

        if (!$employees) {
            return $this->errorResponse(null, 'Error de funcionarios', 404);
        }

        if ($employees->isEmpty()) {
            return $this->successResponse($employees, 'No hay empleados disponibles');
        }

        return $this->successResponse($employees, 'Lista de empleados obtenida correctamente');
    }

    // Mostrar un empleado específico
    public function show(int $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return $this->errorResponse(null, 'Empleado no encontrado.', 404);
        }

        return $this->successResponse($employee, 'Empleado encontrado');
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
        $validated['names'] = $validated['first_names'] . " " . $validated['last_names'];

        $employee = Employee::create($validated);

        return $this->successResponse($employee, 'Empleado creado exitosamente', 201);
    }

    // Actualizar un empleado existente
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return $this->errorResponse(null, 'Empleado no encontrado.', 404);
        }

        $validated = $request->validate([
            'first_names' => 'sometimes|required|string|max:100',
            'last_names' => 'sometimes|required|string|max:100',
            'dni' => 'sometimes|required|string|max:20|unique:employees,dni,' . $employee->id,
            'email' => 'nullable|email|max:100',
            // Agrega aquí otras validaciones según tu modelo
        ]);

        $validated['names'] = $validated['first_names'] . " " . $validated['last_names'];
        $employee->update($validated);
        return $this->successResponse($employee, 'Empleado actualizado exitosamente', 200);
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
