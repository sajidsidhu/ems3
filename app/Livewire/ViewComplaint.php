<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\EmployeeComplainte;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('View Complaint')]
class ViewComplaint extends Component
{
    public $complaint;
    public $editMode = false;
    public $assigneeId;
    public $remarks;
    public $canEditComplaint = false;
    
    protected $rules = [
        'complaint.complaint_subject' => 'required|string|max:255',
        'complaint.complaint' => 'required|string',
        'assigneeId' => 'nullable|exists:users,id',
        'remarks' => 'nullable|string|max:500',
    ];

    public function mount($id)
    {
        $this->complaint = EmployeeComplainte::with(['creator', 'assignee'])->findOrFail($id);
        
        // Check if user has access to this complaint
        if (!$this->canViewComplaint()) {
            abort(403, 'Unauthorized action.');
        }

        $this->canEditComplaint = $this->canEditComplaint();
    }

    public function toggleEdit()
    {
        if (!$this->canEditComplaint()) {
            session()->flash('error', 'You are not authorized to edit this complaint.');
            return;
        }
        $this->editMode = !$this->editMode;
    }

    public function assign()
    {
        if (!$this->canAssignComplaint()) {
            session()->flash('error', 'You are not authorized to assign complaints.');
            return;
        }

        $this->validate([
            'assigneeId' => 'required|exists:users,id'
        ]);

        $this->complaint->assigned_to = $this->assigneeId;
        $this->complaint->save();

        session()->flash('success', 'Complaint assigned successfully.');
        $this->editMode = false;
    }

    public function resolve()
    {
        if (!$this->canResolveComplaint()) {
            session()->flash('error', 'You are not authorized to resolve this complaint.');
            return;
        }

        $this->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $this->complaint->status = 'Resolved';
        $this->complaint->remarks = $this->remarks;
        $this->complaint->resolved_by = Auth::id();
        $this->complaint->resolved_at = now();
        $this->complaint->save();

        session()->flash('success', 'Complaint resolved successfully.');
        $this->editMode = false;
    }

    public function save()
    {
        if (!$this->canEditComplaint()) {
            session()->flash('error', 'You are not authorized to edit this complaint.');
            return;
        }

        $this->validate();
        $this->complaint->save();

        session()->flash('success', 'Complaint updated successfully.');
        $this->editMode = false;
    }

    protected function canViewComplaint()
    {
        $user = Auth::user();
        return $user->role === 'HR Administrator' || 
               $user->role === 'HR' || 
               $this->complaint->employee_id === $user->id || 
               $this->complaint->assigned_to === $user->id;
    }

    protected function canEditComplaint()
    {
        $user = Auth::user();
        return $this->complaint->employee_id === $user->id && 
               $this->complaint->status === 'Pending';
    }

    protected function canAssignComplaint()
    {
        $user = Auth::user();
        return $user->role === 'HR Administrator' || 
               $user->role === 'HR';
    }

    protected function canResolveComplaint()
    {
        $user = Auth::user();
        return $user->role === 'Maintenance Staff' && 
               $this->complaint->assigned_to === $user->id && 
               $this->complaint->status === 'Pending';
    }

    public function render()
    {
        $maintenanceStaff = User::where('role', 'Maintenance Staff')->get();
        
        return view('livewire.view-complaint', [
            'maintenanceStaff' => $maintenanceStaff
        ]);
    }
}