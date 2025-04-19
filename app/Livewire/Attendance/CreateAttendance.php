<?php
namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Auth;

class CreateAttendance extends Component
{
    public $employee_id;
    public $date;
    public $clock_in;
    public $clock_out;
    public $status = 'Present';
    public $remarks;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date|unique:employee_attendances,date,NULL,id,employee_id,'.':employee_id',
        'clock_in' => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i|after:clock_in',
        'status' => 'required|in:Present,Absent,Half Day',
        'remarks' => 'nullable|string|max:500'
    ];

    public function save()
    {
        $this->validate();

        EmployeeAttendance::create([
            ...$this->only('employee_id', 'date', 'clock_in', 'clock_out', 'status', 'remarks'),
            'created_by' => Auth::id()
        ]);

        $this->reset();
        session()->flash('success', 'Attendance record created successfully');
        return redirect()->route('attendance.index');
    }

    public function render()
    { 
        return view('livewire.attendance.create-attendance', [
            'employees' => Employee::all()
        ]);
    }
}