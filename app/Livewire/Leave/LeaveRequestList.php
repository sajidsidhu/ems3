<?php 
namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LeaveRequestList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'desc';
    public $showEditForm = false;
    public $leaveRequestId;
    public $leave_type_id;
    public $start_date;
    public $end_date;
    public $reason;

    protected $rules = [
        'leave_type_id' => 'required|exists:leave_types,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:500',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function approve($id)
    {
        $request = LeaveRequest::findOrFail($id);
        // Update status and balance
        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);
        session()->flash('success', 'Leave request approved successfully.');
    }

    public function reject($id)
    {
        $request = LeaveRequest::findOrFail($id);
        // Update status
        $request->update([
            'status' => 'rejected',
            'rejection_reason' => 'Reason for rejection', // Replace with actual input
        ]);

        session()->flash('success', 'Leave request rejected successfully.');
    }

    public function changeStatus($id, $status)
    {
        $request = LeaveRequest::findOrFail($id);
        $request->update(['status' => $status]);
        session()->flash('success', "Leave request status changed to {$status}.");
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function edit($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $this->leaveRequestId = $leaveRequest->id;
        $this->leave_type_id = $leaveRequest->leave_type_id;
        $this->start_date = $leaveRequest->start_date;
        $this->end_date = $leaveRequest->end_date;
        $this->reason = $leaveRequest->reason;
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate();

        LeaveRequest::findOrFail($this->leaveRequestId)->update([
            'leave_type_id' => $this->leave_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
        ]);

        session()->flash('success', 'Leave request updated successfully.');

        $this->resetForm(); // Reset the form
        $this->emit('refreshComponent'); // Emit an event to refresh the component
    }

    public function resetForm()
    {
        $this->reset(['leaveRequestId', 'leave_type_id', 'start_date', 'end_date', 'reason', 'showEditForm']);
    }

    public function delete($id)
    {
        LeaveRequest::findOrFail($id)->delete();
        session()->flash('success', 'Leave request deleted successfully.');
    }

    public function render()
    {
        return view('livewire.leave.leave-request-list', [
            'leaveRequests' => LeaveRequest::with('employee', 'leaveType')
                ->whereHas('employee', function ($query) {
                    $query->where('first_name', 'like', "%{$this->search}%")
                          ->orWhere('last_name', 'like', "%{$this->search}%");
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(10),
            'leaveTypes' => LeaveType::all(), // Pass LeaveType data to the view
        ]);
    }
}