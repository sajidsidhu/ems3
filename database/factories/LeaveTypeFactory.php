<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{

    protected $model = LeaveType::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Sick Leave', 'Vacation Leave', 'Maternity Leave', 'Paternity Leave', 'Bereavement Leave']),
            'description' => $this->faker->sentence,
            'days' => $this->faker->numberBetween(5, 12),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
        ];
    }
}
