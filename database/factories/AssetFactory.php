<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'esbye_code' => $this->faker->unique()->numberBetween(10000, 9999999),
            'description' => $this->faker->sentence(5),
            'serie' => $this->faker->unique()->ean13(),
            'model' => $this->faker->word(),
            'condition' => $this->faker->randomElement(['Bueno', 'Regular', 'Malo']),
            'book_value' => $this->faker->randomFloat(2, 100, 5000),
            'employee_id' => Employee::factory(),
            'department_id' => Department::factory(),
            'inactive' => $this->faker->boolean(10), // 10% de probabilidad de ser inactivo
            'registered_esbye' => $this->faker->boolean(),
            'comments' => $this->faker->paragraph(2),
            'origin' => $this->faker->word(),
        ];
    }
}
