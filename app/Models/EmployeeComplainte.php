<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeComplainte extends Model
{
    use HasFactory;

    protected $fillable = ['complaint_subject', 'employee_id', 'complaint', 'status', 'remarks', 'resolved_by', 'resolved_at', 'assigned_to'];
}
