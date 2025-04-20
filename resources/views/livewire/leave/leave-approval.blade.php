<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Leave Approvals</h2>

    <div class="mb-4 flex gap-4">
        <flux:select wire:model="filterStatus">
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="approved">Approved</flux:select.option>
            <flux:select.option value="rejected">Rejected</flux:select.option>
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
                @foreach($requests as $request)
                <tr>
                    <td>{{ $request->employee->full_name }}</td>
                    <td>{{ $request->leaveType->name }}</td>
                    <td>{{ $request->start_date }} - {{ $request->end_date }}</td>
                    <td>{{ $request->days_requested }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>
                        <flux:badge variant="{{ $request->status === 'approved' ? 'primary' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($request->status) }}
                        </flux:badge>
                    </td>
                    <td class="flex gap-2">
                        @if($request->status === 'pending')
                            <flux:button wire:click="approve({{ $request->id }})" variant="primary">Approve</flux:button>
                            <flux:button wire:click="reject({{ $request->id }})" variant="danger">Reject</flux:button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>