<?php

namespace App\Livewire;

use App\Models\EmployeeComplainte;
use App\Models\User;
use Livewire\Component;

class ComplaintAssignment extends Component
{
    public $complaints;
    public $assignedTo;
    public $selectedComplaint;
    public $complaint_subject;

    public function mount()
    {
        $this->complaints = EmployeeComplainte::where('status', 'Pending')->get();
    }

    public function assign()
    {
        $this->validate([
            'selectedComplaint' => 'required|exists:employee_complaintes,id',
            'assignedTo' => 'required|exists:users,id',
        ]);

        $complaint = EmployeeComplainte::find($this->selectedComplaint);
        $complaint->assigned_to = $this->assignedTo;
        $complaint->save();

        session()->flash('success', 'Complaint assigned successfully.');
        $this->mount(); // Refresh complaints list
    }

    public function render()
    {
        return view('livewire.complaint-assignment');
    }
}
