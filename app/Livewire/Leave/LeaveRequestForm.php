<?php
namespace App\Livewire\Leave;

use App\Helpers\Helper;
use Livewire\Component;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveRequestForm extends Component
{
    public $leaveTypes = [];
    public $employee_id;
    public $leave_type_id;
    public $start_date;
    public $end_date;
    public $reason;
    
    protected $rules = [
        'leave_type_id' => 'required|exists:leave_types,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:500'
    ];

    public function mount()
    {
        $this->leaveTypes = LeaveType::active()->get();
        $this->employee_id = Auth::user()->employee->id;
    }

    public function submit()
    {
        $this->validate();
        
        DB::transaction(function () {
            $daysRequested = Helper::calculateWorkingDays($this->start_date, $this->end_date); // Replace with the actual method for calculating working days
            
            LeaveRequest::create([
                'employee_id' => $this->employee_id,
                'leave_type_id' => $this->leave_type_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'days_requested' => $daysRequested,
                'reason' => $this->reason,
                'created_by' => Auth::id()
            ]);
        });

        session()->flash('success', 'Leave request submitted successfully');
        $this->reset(['leave_type_id', 'start_date', 'end_date', 'reason']);
    }

    public function render()
    {
        return view('livewire.leave.leave-request-form');
    }
}