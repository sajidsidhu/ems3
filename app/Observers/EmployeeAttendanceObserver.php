<?php

namespace App\Observers;

use App\Models\EmployeeAttendance;

class EmployeeAttendanceObserver
{
    /**
     * Handle the EmployeeAttendance "creating" event.
     */
    public function creating(EmployeeAttendance $attendance): void
    {
        $this->calculateWorkingHoursAndLateMinutes($attendance);
    }

    /**
     * Handle the EmployeeAttendance "updating" event.
     */
    public function updating(EmployeeAttendance $attendance): void
    {
        $this->calculateWorkingHoursAndLateMinutes($attendance);
    }

    /**
     * Calculate working hours and late minutes based on clock-in and clock-out times
     */
    private function calculateWorkingHoursAndLateMinutes(EmployeeAttendance $attendance): void
    {
        // Calculate working hours
        if ($attendance->clock_in && $attendance->clock_out) {
            $clock_in = strtotime($attendance->clock_in);
            $clock_out = strtotime($attendance->clock_out);
            
            // Calculate working hours (rounded to 2 decimal places)
            $attendance->working_hours = round(($clock_out - $clock_in) / 3600, 2);
        }

        // Calculate late minutes
        if ($attendance->clock_in) {
            $scheduled_time = strtotime($attendance->date . ' 08:00:00');
            $actual_time = strtotime($attendance->clock_in);
            
            // Calculate late minutes (only if clock-in is after scheduled time)
            $attendance->late_minutes = max(0, round(($actual_time - $scheduled_time) / 60));
        }

        // Update status based on working hours and late minutes
        if ($attendance->working_hours >= 8) {
            $attendance->status = 'Present';
        } elseif ($attendance->working_hours >= 4) {
            $attendance->status = 'Half Day';
        } else {
            $attendance->status = 'Absent';
        }
    }
} 