<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName() . ' ' . $this->faker->lastName();

        return [
            'dni' => $this->faker->unique()->randomNumber(9, true),
            'first_names' => $firstName,
            'last_names' => $lastName,
            'names' => "{$firstName} {$lastName}",
            'position' => $this->faker->jobTitle(),
            'department_id' => Department::factory(),
        ];
    }

    /**
     * Indicate that the employee is Ronny.
     *
     * @return static
     */
    public function adminName()
    {
        return $this->state(fn(array $attributes) => [
            'dni' => '0926605635',
            'first_names' => 'Ronny Eduardo',
            'last_names' => 'Vera Cortázar',
            'names' => 'Ronny Eduardo Vera Cortázar',
            'position' => 'Analista Zonal de Adquisiciones',
            'department_id' => Department::factory(),
        ]);
    }
}
