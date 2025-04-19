<?php
namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Auth;

class EditAttendance extends Component
{
    public $attendance;
    public $employee_id;
    public $date;
    public $clock_in;
    public $clock_out;
    public $status;
    public $remarks;

    protected $rules = [
        'date' => 'required|date|unique:employee_attendances,date,' . ':id,id,employee_id,' . ':employee_id',
        'clock_in' => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i|after:clock_in',
        'status' => 'required|in:Present,Absent,Half Day',
        'remarks' => 'nullable|string|max:500'
    ];

    public function mount(EmployeeAttendance $attendance)
    {
        $this->attendance = $attendance;
        $this->employee_id = $attendance->employee_id;
        $this->date = $attendance->date->format('Y-m-d');
        $this->clock_in = $attendance->clock_in?->format('H:i');
        $this->clock_out = $attendance->clock_out?->format('H:i');
        $this->status = $attendance->status;
        $this->remarks = $attendance->remarks;
    }

    public function update()
    {
        $this->validate();

        $this->attendance->update([
            'date' => $this->date,
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'updated_by' => Auth::id()
        ]);

        session()->flash('success', 'Attendance updated successfully');
        return redirect()->route('attendance.index');
    }

    public function render()
    {
        return view('livewire.attendance.edit-attendance');
    }
}