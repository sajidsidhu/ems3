<?php
namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LeaveApproval extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'pending';

    public function approve($id)
    {
        $request = LeaveRequest::findOrFail($id);
        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);
        
        // Update leave balance
        $balance = LeaveBalance::firstOrCreate([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'year' => now()->year
        ]);
        
        $balance->decrement('balance', $request->days_requested);
        $balance->increment('used', $request->days_requested);
    }

    public function reject($id)
    {
        $request = LeaveRequest::findOrFail($id);
        $request->update([
            'status' => 'rejected',
            'rejection_reason' => 'Reason for rejection', // Replace with actual input
        ]);

        session()->flash('success', 'Leave request rejected successfully.');
    }

    public function render()
    {
        return view('livewire.leave.leave-approval', [
            'requests' => LeaveRequest::with(['employee', 'leaveType'])
                ->where('status', $this->filterStatus)
                ->paginate(10)
        ]);
    }
}