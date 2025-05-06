<?php

namespace App\Livewire\Complaint;

use App\Models\EmployeeComplainte;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Complaint List')]
class ComplaintList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $complaintToDelete = null;

    protected $rules = [
        'search' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->complaintToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->complaintToDelete = null;
    }

    public function delete()
    {
        $complaint = EmployeeComplainte::findOrFail($this->complaintToDelete);
        
        // Check if user has permission to delete
        $user = Auth::user();
        if (!in_array($user->role, ['HR Administrator', 'HR']) && 
            $complaint->employee_id !== $user->id) {
            session()->flash('error', 'You are not authorized to delete this complaint.');
            return;
        }

        $complaint->delete();
        session()->flash('success', 'Complaint deleted successfully.');
        $this->complaintToDelete = null;
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
        return view('livewire.complaint.complaint-list', compact('complaints'));
    }
}
