<?php

namespace App\Livewire\Complaint;

use App\Models\EmployeeComplainte;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Edit Complaint')]
class EditComplaint extends Component
{
    public $complaint;
    public $complaint_subject;
    public $complaint_text;
    public $status;
    public $remarks;
    public $assigned_to;

    protected $rules = [
        'complaint_subject' => 'required|string|max:255',
        'complaint_text' => 'required|string',
        'status' => 'required|in:Pending,Resolved',
        'remarks' => 'nullable|string|max:500',
        'assigned_to' => 'nullable|exists:users,id'
    ];

    public function mount($id)
    {
        $this->complaint = EmployeeComplainte::with(['creator', 'assignee'])->findOrFail($id);
        
        // Check if user has access to edit this complaint
        if (!$this->canEditComplaint()) {
            abort(403, 'Unauthorized action.');
        }

        $this->complaint_subject = $this->complaint->complaint_subject;
        $this->complaint_text = $this->complaint->complaint;
        $this->status = $this->complaint->status;
        $this->remarks = $this->complaint->remarks;
        $this->assigned_to = $this->complaint->assigned_to;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function update()
    {
        $this->validate();

        // Only HR can change status and assign complaints
        if (!in_array(Auth::user()->role, ['HR Administrator', 'HR'])) {
            $this->status = $this->complaint->status;
            $this->assigned_to = $this->complaint->assigned_to;
        }

        $this->complaint->update([
            'complaint_subject' => $this->complaint_subject,
            'complaint' => $this->complaint_text,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'assigned_to' => $this->assigned_to,
        ]);

        if ($this->status === 'Resolved') {
            $this->complaint->update([
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);
        }

        session()->flash('success', 'Complaint updated successfully.');
        return redirect()->route('complaints.view', ['id' => $this->complaint->id]);
    }

    protected function canEditComplaint()
    {
        $user = Auth::user();
        return $user->role === 'HR Administrator' || 
               $user->role === 'HR' || 
               $this->complaint->employee_id === $user->id;
    }

    public function render()
    {
        return view('livewire.complaint.edit-complaint', [
            'maintenanceStaff' => \App\Models\User::where('role', 'Maintenance Staff')->get()
        ]);
    }
} 