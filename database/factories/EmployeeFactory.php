<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Creates a user for each employee
            'employee_id' => 'EMP-' . $this->faker->unique()->randomNumber(5),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'position' => $this->faker->randomElement(['Mill Manager', 'Production Supervisor', 'Quality Control Officer', 'Machine Operator', 'Maintenance Technician', 'Logistics Coordinator']),
            'salary' => $this->faker->randomFloat(2, 30000, 100000), // Salary range
            'hire_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'profile_photo_path' => null
            
        ];
    }
}
