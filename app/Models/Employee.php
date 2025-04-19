<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name', 'phone', 'position', 'salary', 'hire_date', 'status', 
        'profile_photo_path', 'resignation_date', 'resignation_reason'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public static function gettAll()
    {
        return self::all();
    }

    public static function getByID($id)
    {
        return self::find($id);
    }

    public static function getByUserID($id)
    {
        return self::where('user_id', $id)->first();
    }
    
    public static function add($data)
    {
        self::create($data);
    }

    public static function updateEmployee($id, $data)
    {
        $employee = self::getByID($id);
        $employee->update($data);
        return $employee;

    }

    public function deleteByUserID($user_id)
    {
        self::where('user_id', $user_id)->delete();
        User::deleteByID($user_id);
    }

}
