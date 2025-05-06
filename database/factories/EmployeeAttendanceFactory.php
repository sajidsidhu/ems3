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
        $check_in = clone $date;
        $check_in->setTime(8, 0, 0);
        $check_in->modify('+' . $this->faker->numberBetween(-30, 30) . ' minutes');
        
        $check_out = clone $check_in;
        $check_out->modify('+' . $this->faker->numberBetween(8, 9) . ' hours');

        return [
            'employee_id' => Employee::factory(),
            'date' => $date->format('Y-m-d'),
            'check_in' => $check_in,
            'check_out' => $check_out,
            'status' => $this->faker->randomElement(['present', 'late', 'absent']),
            'working_hours' => function (array $attributes) {
                return round((strtotime($attributes['check_out']) - strtotime($attributes['check_in'])) / 3600, 2);
            },
            'late_minutes' => function (array $attributes) {
                $scheduled_time = strtotime($attributes['date'] . ' 08:00:00');
                $actual_time = strtotime($attributes['check_in']);
                return max(0, round(($actual_time - $scheduled_time) / 60));
            },
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
} 