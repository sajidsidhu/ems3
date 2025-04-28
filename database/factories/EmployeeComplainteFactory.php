<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeComplainte>
 */
class EmployeeComplainteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => User::factory(),
            'complaint' => $this->faker->sentence,
            'complaint_subject' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['Pending', 'Resolved']),
            'remarks' => $this->faker->optional()->sentence,
            'resolved_by' => null,
            'resolved_at' => null,
            'assigned_to' => null,
        ];
    }
}
