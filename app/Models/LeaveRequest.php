<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 
        'leave_type_id',
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'status',
        'approved_by',
        'rejection_reason'
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            $leaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $model->leave_type_id, date('Y', strtotime($model->start_date)));
            $leaveBalance->decrement('balance', $model->days_requested);
            $leaveBalance->increment('used', $model->days_requested);
        });

        static::updating(function ($model) {
            $original_leave_type = $model->getOriginal('leave_type_id');
            $original_days = $model->getOriginal('days_requested');
            $original_status = $model->getOriginal('status');
            $new_leave_type = $model->leave_type_id;
            $new_days = $model->days_requested;
            $new_status = $model->status;
            $year = date('Y', strtotime($model->start_date));

            // If leave type or days change, revert old balance and apply new
            if ($model->isDirty('leave_type_id') || $model->isDirty('days_requested')) {
                // Revert old balance
                $oldLeaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $original_leave_type, $year);
                $oldLeaveBalance->increment('balance', $original_days);
                $oldLeaveBalance->decrement('used', $original_days);
                // Apply new balance
                $newLeaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $new_leave_type, $year);
                $newLeaveBalance->decrement('balance', $new_days);
                $newLeaveBalance->increment('used', $new_days);
            }

            // If status changes
            if ($model->isDirty('status')) {
                $leaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $new_leave_type, $year);
                if ($new_status == 'rejected') {
                    $leaveBalance->increment('balance', $new_days);
                    $leaveBalance->decrement('used', $new_days);
                } elseif ($original_status == 'rejected') {
                    $leaveBalance->decrement('balance', $new_days);
                    $leaveBalance->increment('used', $new_days);
                }
            }
        });

        static::deleting(function ($model) {
            $leaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $model->leave_type_id, date('Y', strtotime($model->start_date)));
            $leaveBalance->increment('balance', $model->days_requested);
            $leaveBalance->decrement('used', $model->days_requested);
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
