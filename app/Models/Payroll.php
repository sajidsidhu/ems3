<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_month',
        'basic_salary',
        'total_working_days',
        'total_present_days',
        'total_absent_days',
        'total_late_arrivals',
        'total_late_minutes',
        'total_leaves_taken',
        'total_working_hours',
        'total_deductions',
        'total_additions',
        'net_salary',
        'status',
        'approved_by',
        'approved_at',
        'paid_at',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'payroll_month' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'basic_salary' => 'decimal:2',
        'total_working_hours' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_additions' => 'decimal:2',
        'net_salary' => 'decimal:2'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function calculateNetSalary(): void
    {
        $this->net_salary = $this->basic_salary + $this->total_additions - $this->total_deductions;
    }
} 