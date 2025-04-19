<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        LeaveType::create([
            'name' => 'Sick Leave',
            'slug' => 'sick-leave',
            'default_days' => 10,
            'is_active' => true,
            'description' => 'Leave for medical reasons.'
        ]);

        LeaveType::create([
            'name' => 'Vacation Leave',
            'slug' => 'vacation-leave',
            'default_days' => 15,
            'is_active' => true,
            'description' => 'Leave for personal vacation.'
        ]);

        LeaveType::create([
            'name' => 'Maternity Leave',
            'slug' => 'maternity-leave',
            'default_days' => 90,
            'is_active' => true,
            'description' => 'Leave for maternity purposes.'
        ]);

        LeaveType::create([
            'name' => 'Paternity Leave',
            'slug' => 'paternity-leave',
            'default_days' => 7,
            'is_active' => true,
            'description' => 'Leave for paternity purposes.'
        ]);

        LeaveType::create([
            'name' => 'Bereavement Leave',
            'slug' => 'bereavement-leave',
            'default_days' => 5,
            'is_active' => true,
            'description' => 'Leave for family loss.'
        ]);
    }
}