<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeAttendanceFactory extends Factory
{
    protected $model = EmployeeAttendance::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-4 months', 'now');
        $clock_in = clone $date;
        $clock_in->setTime(8, 0, 0);
        $clock_in->modify('+' . $this->faker->numberBetween(-30, 30) . ' minutes');
        
        $clock_out = clone $clock_in;
        $clock_out->modify('+' . $this->faker->numberBetween(8, 9) . ' hours');

        $date_str = $date->format('Y-m-d');
        $clock_in_str = $clock_in->format('Y-m-d H:i:s');
        $clock_out_str = $clock_out->format('Y-m-d H:i:s');

        return [
            'employee_id' => Employee::factory(),
            'date' => $date_str,
            'clock_in' => $clock_in_str,
            'clock_out' => $clock_out_str,
            'status' => $this->faker->randomElement(['Present', 'Half Day', 'Absent']),
            'working_hours' => round((strtotime($clock_out_str) - strtotime($clock_in_str)) / 3600, 2),
            'late_minutes' => max(0, round((strtotime($clock_in_str) - strtotime($date_str . ' 08:00:00')) / 60)),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
} 