<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeComplainte extends Model
{
    use HasFactory;

    protected $fillable = ['complaint_subject', 'employee_id', 'complaint', 'status', 'remarks', 'resolved_by', 'resolved_at', 'assigned_to'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
