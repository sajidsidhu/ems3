<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">New Leave Request</h2>

    <form wire:submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Leave Type</flux:label>
                <flux:select wire:model="leave_type_id">
                    <flux:select.option value="">Select Leave Type</flux:select.option>
                    @foreach($leaveTypes as $type)
                        <flux:select.option value="{{ $type->id }}">
                            {{ $type->name }} ({{ $type->days }} days)
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Start Date</flux:label>
                <flux:input type="date" wire:model="start_date" />
            </flux:field>

            <flux:field>
                <flux:label>End Date</flux:label>
                <flux:input type="date" wire:model="end_date" />
            </flux:field>

            <div class="md:col-span-2">
                <flux:checkbox wire:model="showTimeFields" label="Partial Day Leave" />
            </div>

            @if($showTimeFields)
                <flux:field>
                    <flux:label>Start Time</flux:label>
                    <flux:input type="time" wire:model="start_time" />
                </flux:field>

                <flux:field>
                    <flux:label>End Time</flux:label>
                    <flux:input type="time" wire:model="end_time" />
                </flux:field>
            @endif

            <flux:field class="md:col-span-2">
                <flux:label>Total Days</flux:label>
                <flux:input type="number" wire:model="days" readonly />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>Reason</flux:label>
                <flux:textarea wire:model="reason" rows="4" placeholder="Enter leave reason..." />
            </flux:field>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <flux:button as="a" href="{{ route('leave.requests') }}" variant="ghost">Cancel</flux:button>
            <flux:button type="submit" variant="primary">Submit Request</flux:button>
        </div>
    </form>
</div>