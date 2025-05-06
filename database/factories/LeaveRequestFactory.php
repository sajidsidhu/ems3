<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition(): array
    {
        $start_date = $this->faker->dateTimeBetween('-4 months', '+2 months');
        $end_date = clone $start_date;
        $end_date->modify('+' . $this->faker->numberBetween(1, 5) . ' days');
        $leave_type = LeaveType::get('id')->random();
        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => $leave_type->id,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'days_requested' => function (array $attributes) {
                return (strtotime($attributes['end_date']) - strtotime($attributes['start_date'])) / (60 * 60 * 24) + 1;
            },
            'reason' => $this->faker->paragraph(),
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'created_at' => $start_date,
            'updated_at' => $start_date,
        ];
    }
} 