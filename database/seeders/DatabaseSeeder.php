<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Llama al seeder de tipos de transacción primero
        $this->call(TransactionTypeSeeder::class);

        // Crea 3 departamentos aleatorios
        Department::factory()->count(3)->create();

        // Crea 6 empleados aleatorios
        Employee::factory()->count(6)->create();

        // Crea un empleado específico con el estado 'ronny'
        Employee::factory()->adminName()->create();

        // Crea 5 bienes, asignando un empleado y departamento existente a cada uno
        Asset::factory()->count(5)->create();
    }
}
