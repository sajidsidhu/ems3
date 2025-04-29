<?php

namespace App\Livewire;

use App\Models\EmployeeComplainte;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ComplaintList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $assigneeId;
    public $showAssignModal = false;
    public $selectedComplaint;
    public $showModal = false;

    protected $rules = [
        'assigneeId' => 'nullable|exists:users,id',
        'search' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'assigneeId.exists' => 'The selected assignee does not exist.',
        'search.max' => 'Search query must not exceed 255 characters.',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = EmployeeComplainte::with(['creator', 'assignee']);

        // Apply search filter regardless of user role
        if ($this->search) {
            $query->where(function($q) {
                $q->where('complaint_subject', 'like', '%' . $this->search . '%')
                  ->orWhere('complaint', 'like', '%' . $this->search . '%')
                  ->orWhere('status', 'like', '%' . $this->search . '%')
                  ->orWhereHas('creator', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('assignee', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply role-based filters after search
        if (!in_array($user->role, ['HR Administrator', 'HR'])) {
            $query->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        $complaints = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.complaint-list', compact('complaints'));
    }

    public function viewComplaint($id)
    {
        $this->selectedComplaint = EmployeeComplainte::with(['creator', 'assignee'])->findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showAssignModal = false;
    }

    public function editComplaint($id)
    {
        $complaint = EmployeeComplainte::findOrFail($id);

        if ($complaint->employee_id !== Auth::id()) {
            session()->flash('error', 'You are not authorized to edit this complaint.');
            return;
        }

        // Logic for editing the complaint (e.g., redirect to edit page or open modal)
    }

    public function deleteComplaint($id)
    {
        $complaint = EmployeeComplainte::findOrFail($id);

        if ($complaint->employee_id !== Auth::id()) {
            session()->flash('error', 'You are not authorized to delete this complaint.');
            return;
        }

        $complaint->delete();
        session()->flash('success', 'Complaint deleted successfully.');
        $this->mount(); // Refresh the complaints list
    }

    public function openAssignModal($id)
    {
        $this->selectedComplaint = EmployeeComplainte::findOrFail($id);
        $this->showAssignModal = true;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function assignToUser()
    {
        $this->validate([
            'assigneeId' => 'required|exists:users,id',
        ], [
            'assigneeId.required' => 'Please select a user to assign the complaint.',
            'assigneeId.exists' => 'The selected user does not exist.',
        ]);

        $this->selectedComplaint->assigned_to = $this->assigneeId;
        $this->selectedComplaint->save();

        session()->flash('success', 'Complaint assigned successfully.');
        $this->showAssignModal = false;
        $this->mount(); // Refresh complaints list
    }

    public function resolveComplaint($id)
    {
        $complaint = EmployeeComplainte::findOrFail($id);

        if (auth()->user()->role !== 'HR Administrator') {
            session()->flash('error', 'You are not authorized to resolve complaints.');
            return;
        }

        $complaint->status = 'Resolved';
        $complaint->resolved_by = auth()->id();
        $complaint->resolved_at = now();
        $complaint->save();

        session()->flash('success', 'Complaint marked as resolved.');
        $this->mount(); // Refresh the complaints list
    }

    protected function getAuthenticatedUser()
    {
        return auth()->check() ? auth()->user() : null;
    }
}
