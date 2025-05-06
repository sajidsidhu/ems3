<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'HR Administrator',
            'status' => 'Active',
        ]);

        // Create regular users
        User::factory(10)->create();

        // Create leave types
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'default_days' => 20,
                'is_active' => true,
                'description' => 'Regular annual leave',
            ],
            [
                'name' => 'Sick Leave',
                'default_days' => 10,
                'is_active' => true,
                'description' => 'Medical leave',
            ],
            [
                'name' => 'Casual Leave',
                'default_days' => 5,
                'is_active' => true,
                'description' => 'Short-term leave',
            ],
            [
                'name' => 'Maternity Leave',
                'default_days' => 90,
                'is_active' => true,
                'description' => 'For expecting mothers',
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'default_days' => $type['default_days'],
                'is_active' => $type['is_active'],
                'description' => $type['description'],
            ]);
        }

        // Create employees with related data
        User::where('email', '!=', 'admin@example.com')->each(function ($user) {
            $employee = Employee::factory()->create([
                'user_id' => $user->id,
            ]);

            // Create leave balances for each leave type
            LeaveType::all()->each(function ($leaveType) use ($employee) {
                LeaveBalance::factory()->create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'balance' => $leaveType->default_days,
                    'used' => 0,
                    'year' => now()->year,
                ]);
            });

            // Create leave requests
            LeaveRequest::factory(3)->create([
                'employee_id' => $employee->id,
            ]);

            // Create attendance records for the last 4 months
            $startDate = now()->subMonths(4)->startOfMonth();
            $endDate = now();

            for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
                if ($date->isWeekday()) {
                    EmployeeAttendance::factory()->create([
                        'employee_id' => $employee->id,
                        'date' => $date->format('Y-m-d'),
                    ]);
                }
            }

            // Create payroll records for the last 4 months
            for ($i = 0; $i < 4; $i++) {
                $payrollMonth = now()->subMonths($i)->format('Y-m');
                $payroll = Payroll::factory()->create([
                    'employee_id' => $employee->id,
                    'payroll_month' => $payrollMonth,
                ]);

                // Create payroll details
                PayrollDetail::factory(2)->create([
                    'payroll_id' => $payroll->id,
                    'type' => 'deduction',
                ]);

                PayrollDetail::factory(2)->create([
                    'payroll_id' => $payroll->id,
                    'type' => 'addition',
                ]);
            }
        });
    }
}
