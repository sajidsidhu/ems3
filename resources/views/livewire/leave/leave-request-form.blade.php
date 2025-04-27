
<flux:fieldset>
    <form wire:submit.prevent="submit" class="space-y-4">
        <flux:legend class="text-xl font-bold mb-4">Apply Leave</flux:legend>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:field>
                <flux:label>Leave Type</flux:label>
                <flux:select wire:model="leave_type_id">
                    <flux:select.option value="">Select Leave Type</flux:select.option>
                    @foreach($leaveTypes as $type)
                        <flux:select.option value="{{ $type->id }}">
                            {{ $type->name }} ({{ $type->default_days }} days)
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

            <flux:field class="md:col-span-3">
                <flux:label>Reason</flux:label>
                <flux:textarea wire:model="reason" rows="4" placeholder="Enter reason for leave..." />
            </flux:field>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <flux:button as="a" href="{{ route('leave.requests') }}" variant="ghost">Cancel</flux:button>
            <flux:button type="submit" variant="primary">Submit Request</flux:button>
        </div>
    </form>

</flux:fieldset>