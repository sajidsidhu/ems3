<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    protected $fillable = ['employee_id', 'leave_type_id', 'balance', 'used', 'year'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public static function initializeBalance($employeeId, $leaveTypeId, $year)
    {
        return self::firstOrCreate(
            [
                'employee_id' => $employeeId,
                'leave_type_id' => $leaveTypeId,
                'year' => $year
            ],
            [
                'balance' => LeaveType::find($leaveTypeId)->default_days,
                'used' => 0
            ]
        );
    }

    public static function getEmployeeLeaveBalance($employeeId, $leaveTypeId, $year)
    {
        $balance =  self::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();
        if ($balance) {
            return $balance;
        }
        
       $balance = self::initializeBalance($employeeId, $leaveTypeId, $year);
        return $balance;
    }
}
