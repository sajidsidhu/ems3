<div class="px-6">
    <h2 class="text-xl font-bold mb-4">Leave Requests</h2>

    <div class="flex justify-between items-center mb-4">
        <flux:input icon-trailing="magnifying-glass" type="text" wire:model.live.debounce.500ms="search" placeholder="Search by employee name" class="max-w-sm" />
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if ($showEditForm)
        <flux:fieldset>
            <h3 class="text-lg font-semibold mb-4">Edit Leave Request</h3>
            <form wire:submit.prevent="update">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="leave_type_id" class="block text-sm font-medium mb-1">Leave Type</label>
                        <flux:select id="leave_type_id" wire:model="leave_type_id">
                            <flux:select.option value="">Select Leave Type</flux:select.option>
                            @foreach ($leaveTypes as $type)
                                <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('leave_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium mb-1">Start Date</label>
                        <flux:input type="date" id="start_date" wire:model="start_date" />
                        @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium mb-1">End Date</label>
                        <flux:input type="date" id="end_date" wire:model="end_date" />
                        @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label for="reason" class="block text-sm font-medium mb-1">Reason</label>
                        <flux:textarea id="reason" wire:model="reason"></flux:textarea>
                        @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <flux:button type="button" wire:click="resetForm" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Update</flux:button>
                </div>
            </form>
        </flux:fieldset>
    @endif

    @if(!$showEditForm)
    <table class="mt-4 border-collapse border max-w-full w-full mb-4">
        <thead>
            <tr>
                <th class="border p-2 cursor-pointer" wire:click="sort('employee_id')">Employee</th>
                <th class="border p-2 cursor-pointer" wire:click="sort('leave_type_id')">Leave Type</th>
                <th class="border p-2 cursor-pointer" wire:click="sort('start_date')">Start Date</th>
                <th class="border p-2 cursor-pointer" wire:click="sort('end_date')">End Date</th>
                <th class="border p-2 cursor-pointer" wire:click="sort('status')">Status</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveRequests as $request)
                <tr>
                    <td class="border p-2">{{ $request->employee->first_name }} {{ $request->employee->last_name }}</td>
                    <td class="border p-2">{{ $request->leaveType->name }}</td>
                    <td class="border p-2">{{ $request->start_date }}</td>
                    <td class="border p-2">{{ $request->end_date }}</td>
                    <td class="border p-2">
                        <flux:badge color="{{ $request->status === 'approved' ? 'green' : ($request->status === 'rejected' ? 'red' : 'orange') }}">
                            {{ ucfirst($request->status) }}
                        </flux:badge>
                    </td>
                    <td class="border p-2 flex gap-2">
                        @if ($request->status !== 'approved')
                            <flux:badge as="button" wire:click="changeStatus({{ $request->id }}, 'approved')" variant="solid" color="green">Approve</flux:badge>
                        @endif
                        @if ($request->status !== 'rejected')
                            <flux:badge as="button" wire:click="changeStatus({{ $request->id }}, 'rejected')" variant="solid" color="rose">Reject</flux:badge>
                        @endif
                        @if ($request->status !== 'pending')
                            <flux:badge as="button" wire:click="changeStatus({{ $request->id }}, 'pending')" variant="solid" color="orange">Set Pending</flux:badge>
                        @endif
                        <flux:badge as="button" wire:click="edit({{ $request->id }})" variant="solid" color="yellow">Edit</flux:badge>
                        <flux:badge as="button" wire:click="delete({{ $request->id }})" variant="solid" color="red">Delete</flux:badge>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $leaveRequests->links() }}

    @endif
</div>