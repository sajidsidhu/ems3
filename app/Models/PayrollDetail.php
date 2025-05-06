<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'payroll_id',
        'type',
        'description',
        'amount',
        'remarks'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
} 