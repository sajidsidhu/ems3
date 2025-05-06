<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Support\Str;
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
        $name = $this->faker->unique()->randomElement(['Sick Leave', 'Vacation Leave', 'Maternity Leave', 'Paternity Leave', 'Bereavement Leave']);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'default_days' => $this->faker->numberBetween(8, 12),
            'is_active' => true,
            'description' => $this->faker->sentence,
        ];
    }
}
