<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Edit Attendance Record</h2>

    @if(session('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Employee Selection (Read-only for edit) -->
            <flux:field>
                <flux:label>Employee</flux:label>
                <flux:input 
                    type="text" 
                    value="{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}" 
                    readonly 
                />
            </flux:field>

            <!-- Date Input -->
            <flux:field>
                <flux:label>Date</flux:label>
                <flux:input type="date" wire:model="date"/>
            </flux:field>

            <!-- Clock In/Out -->
            <flux:field>
                <flux:label>Clock In</flux:label>
                <flux:input type="time" wire:model="clock_in"/>
            </flux:field>

            <flux:field>
                <flux:label>Clock Out</flux:label>
                <flux:input type="time" wire:model="clock_out"/>
            </flux:field>

            <!-- Status Selection -->
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:radio.group wire:model="status">
                    <flux:radio value="Present" label="Present"/>
                    <flux:radio value="Absent" label="Absent"/>
                    <flux:radio value="Half Day" label="Half Day"/>
                </flux:radio.group>
            </flux:field>

            <!-- Remarks -->
            <flux:field>
                <flux:label>Remarks</flux:label>
                <flux:textarea wire:model="remarks" placeholder="Additional notes..."></flux:textarea>
            </flux:field>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <flux:button 
                as="a"  
                href="{{ route('attendance.index') }}" 
                variant="ghost"
            >
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Update Attendance
            </flux:button>
        </div>
    </form>
</div>