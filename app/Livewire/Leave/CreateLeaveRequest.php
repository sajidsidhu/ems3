<?php
namespace App\Livewire\Leave;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\LeaveType;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;

class CreateLeaveRequest extends Component
{
    public $leaveTypes;
    public $employee_id;
    public $leave_type_id;
    public $start_date;
    public $end_date;
    public $start_time;
    public $end_time;
    public $days = 0;
    public $reason;
    public $showTimeFields = false;

    protected $rules = [
        'leave_type_id' => 'required|exists:leave_types,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'start_time' => 'nullable|required_with:end_time|date_format:H:i',
        'end_time' => 'nullable|required_with:start_time|date_format:H:i|after:start_time',
        'reason' => 'required|string|max:500'
    ];

    public function mount()
    {
        $this->leaveTypes = LeaveType::where('status', 'Active')->get();
        $this->employee_id = Auth::user()->employee->id;
    }

    public function updated($property)
    {
        if (in_array($property, ['start_date', 'end_date', 'start_time', 'end_time'])) {
            $this->calculateDays();
        }
    }

    private function calculateDays()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            
            $this->days = $start->diffInDays($end) + 1; // Inclusive days
            
            if ($this->showTimeFields && $this->start_time && $this->end_time) {
                $startTime = Carbon::parse($this->start_time);
                $endTime = Carbon::parse($this->end_time);
                
                $hours = $endTime->diffInHours($startTime);
                $this->days = $hours / 8; // Convert hours to days (assuming 8-hour workday)
            }
        }
    }

    public function submit()
    {
        $this->validate();
        
        EmployeeLeave::create([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->showTimeFields ? $this->start_time : null,
            'end_time' => $this->showTimeFields ? $this->end_time : null,
            'days' => $this->days,
            'reason' => $this->reason,
            'status' => 'Pending'
        ]);

        session()->flash('success', 'Leave request submitted successfully');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['leave_type_id', 'start_date', 'end_date', 
                    'start_time', 'end_time', 'days', 'reason']);
    }

    public function render()
    {
        return view('livewire.leave.create-leave-request');
    }
}