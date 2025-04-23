<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
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
            if ($model->isDirty('status')) {
                $origical_status = $model->getOriginal('status');
                $new_status = $model->status;
                $leaveBalance = LeaveBalance::getEmployeeLeaveBalance($model->employee_id, $model->leave_type_id, date('Y', strtotime($model->start_date)));
                if($new_status == 'rejected'){
                    $leaveBalance->increment('balance', $model->days_requested);
                    $leaveBalance->decrement('used', $model->days_requested);
                } elseif ($origical_status == 'rejected') {
                    $leaveBalance->decrement('balance', $model->days_requested);
                    $leaveBalance->increment('used', $model->days_requested);
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
