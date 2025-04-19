<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Leave Approvals</h2>

    <div class="mb-4 flex gap-4">
        <flux:select wire:model="filterStatus">
            <flux:select.option value="Pending">Pending</flux:select.option>
            <flux:select.option value="Approved">Approved</flux:select.option>
            <flux:select.option value="Declined">Declined</flux:select.option>
        </flux:select>
    </div>

    <div class="overflow-x-auto">
        <table class="flux-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Dates</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->full_name }}</td>
                    <td>{{ $leave->leaveType->name }}</td>
                    <td>
                        {{ $leave->start_date->format('M d') }} - 
                        {{ $leave->end_date->format('M d') }}
                        @if($leave->start_time)
                            <br><small>{{ $leave->start_time }} to {{ $leave->end_time }}</small>
                        @endif
                    </td>
                    <td>{{ $leave->days }}</td>
                    <td class="max-w-xs">{{ Str::limit($leave->reason, 50) }}</td>
                    <td>
                        <flux:badge variant="{{ match($leave->status) {
                            'Approved' => 'success',
                            'Declined' => 'danger',
                            default => 'warning'
                        }}">
                            {{ $leave->status }}
                        </flux:badge>
                    </td>
                    <td class="flex gap-2">
                        @if($leave->status === 'Pending')
                            <flux:button wire:click="approve({{ $leave->id }})" variant="success">Approve</flux:button>
                            <flux:button wire:click="reject({{ $leave->id }})" variant="danger">Decline</flux:button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $leaves->links() }}
    </div>
</div>