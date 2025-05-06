<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalanceFactory extends Factory
{
    protected $model = LeaveBalance::class;

    public function definition(): array
    {
        $balance = $this->faker->numberBetween(10, 30);
        $used = $this->faker->numberBetween(0, $balance);
        
        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'balance' => $balance,
            'used' => $used,
            'year' => $this->faker->year(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 