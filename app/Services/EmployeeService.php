<?php

namespace App\Services;

use App\Models\Employee;

final class EmployeeService
{
    /**
     * Busca un empleado por su número de cédula o por su nombre.
     *
     * @param string $searchTerm El término de búsqueda (cédula o nombre).
     * @return Employee|null
     */
    public function findEmployeeByDniOrName(string $searchTerm)
    {
        return Employee::where('id_card', $searchTerm)
            ->orWhere('names', 'like', '%' . $searchTerm . '%')
            ->get();
    }
}
