<?php

namespace App\Models;

use App\Models\EmployeeLeave;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'default_days', 'is_active', 'description'];

    protected $casts = [
        'days' => 'integer',
        'status' => 'string'
    ];
    
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
    

    public function employeeLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
