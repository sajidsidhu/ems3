<?php

namespace Database\Factories;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollDetailFactory extends Factory
{
    protected $model = PayrollDetail::class;

    public function definition(): array
    {
        return [
            'payroll_id' => Payroll::factory(),
            'type' => $this->faker->randomElement(['deduction', 'addition']),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->numberBetween(100, 5000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 