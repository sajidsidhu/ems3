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
        $basic_salary = $this->faker->numberBetween(30000, 100000);
        $total_working_days = $this->faker->numberBetween(20, 26);
        $total_present_days = $this->faker->numberBetween(15, $total_working_days);
        $total_absent_days = $total_working_days - $total_present_days;
        $total_late_arrivals = $this->faker->numberBetween(0, 5);
        $total_late_minutes = $this->faker->numberBetween(0, 120);
        $total_leaves_taken = $this->faker->numberBetween(0, 3);
        $total_working_hours = $this->faker->numberBetween(120, 208);
        $total_deductions = $this->faker->numberBetween(0, 5000);
        $total_additions = $this->faker->numberBetween(0, 5000);
        $net_salary = $basic_salary - $total_deductions + $total_additions;

        return [
            'employee_id' => Employee::factory(),
            'payroll_month' => $month->format('Y-m'),
            'basic_salary' => $basic_salary,
            'total_working_days' => $total_working_days,
            'total_present_days' => $total_present_days,
            'total_absent_days' => $total_absent_days,
            'total_late_arrivals' => $total_late_arrivals,
            'total_late_minutes' => $total_late_minutes,
            'total_leaves_taken' => $total_leaves_taken,
            'total_working_hours' => $total_working_hours,
            'total_deductions' => $total_deductions,
            'total_additions' => $total_additions,
            'net_salary' => $net_salary,
            'status' => $this->faker->randomElement(['draft', 'approved', 'paid']),
            'created_at' => $month,
            'updated_at' => $month,
        ];
    }
} 