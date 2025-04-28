<?php

namespace App\Livewire;

use App\Models\EmployeeComplainte;
use Livewire\Component;

class ComplaintResolution extends Component
{
    public $complaints;
    public $selectedComplaint;
    public $remarks;
    public $complaint_subject;

    public function mount()
    {
        $this->complaints = EmployeeComplainte::where('status', 'Pending')
            ->where('assigned_to', auth()->id())
            ->get();
    }

    public function resolve()
    {
        $this->validate([
            'selectedComplaint' => 'required|exists:employee_complaintes,id',
            'remarks' => 'nullable|string',
        ]);

        $complaint = EmployeeComplainte::find($this->selectedComplaint);
        $complaint->status = 'Resolved';
        $complaint->remarks = $this->remarks;
        $complaint->resolved_by = auth()->id();
        $complaint->resolved_at = now();
        $complaint->save();

        session()->flash('success', 'Complaint resolved successfully.');
        $this->mount(); // Refresh complaints list
    }

    public function render()
    {
        return view('livewire.complaint-resolution');
    }
}
