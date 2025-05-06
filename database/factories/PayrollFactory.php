<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    public function definition(): array
    {
        $month = $this->faker->dateTimeBetween('-4 months', 'now');
        return [
            'employee_id' => Employee::factory(),
            'payroll_month' => $month->format('Y-m'),
            'basic_salary' => $this->faker->numberBetween(30000, 100000),
            'total_working_days' => $this->faker->numberBetween(20, 26),
            'total_working_hours' => $this->faker->numberBetween(160, 208),
            'total_late_minutes' => $this->faker->numberBetween(0, 120),
            'total_absences' => $this->faker->numberBetween(0, 5),
            'total_leaves' => $this->faker->numberBetween(0, 3),
            'total_deductions' => $this->faker->numberBetween(0, 5000),
            'total_additions' => $this->faker->numberBetween(0, 5000),
            'net_salary' => $this->faker->numberBetween(25000, 95000),
            'status' => $this->faker->randomElement(['pending', 'approved', 'paid']),
            'created_at' => $month,
            'updated_at' => $month,
        ];
    }
} 