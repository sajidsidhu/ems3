<div class="max-w-4xl mx-auto p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Edit Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="update" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <!-- Complaint Subject Input -->
            <flux:field>
                <flux:label>Complaint Subject</flux:label>
                <flux:input wire:model="complaint_subject" placeholder="Enter the subject of your complaint..." />
                @error('complaint_subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>

            <!-- Complaint Input -->
            <flux:field>
                <flux:label>Complaint Details</flux:label>
                <flux:textarea wire:model="complaint_text" placeholder="Describe your complaint..." rows="4"></flux:textarea>
                @error('complaint_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>

            @if(in_array(auth()->user()->role, ['HR Administrator', 'HR']))
                <!-- Status Selection -->
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <flux:select.option value="Pending">Pending</flux:select.option>
                        <flux:select.option value="Resolved">Resolved</flux:select.option>
                    </flux:select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </flux:field>

                <!-- Assign To Selection -->
                <flux:field>
                    <flux:label>Assign To</flux:label>
                    <flux:select wire:model="assigned_to">
                        <flux:select.option value="">Select Maintenance Staff</flux:select.option>
                        @foreach($maintenanceStaff as $staff)
                            <flux:select.option value="{{ $staff->id }}">{{ $staff->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </flux:field>
            @endif

            <!-- Remarks Input -->
            <flux:field>
                <flux:label>Remarks</flux:label>
                <flux:textarea wire:model="remarks" placeholder="Enter any remarks..." rows="3"></flux:textarea>
                @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <flux:button as="a" href="{{ route('complaints.view', ['id' => $complaint->id]) }}" variant="ghost">Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update Complaint</flux:button>
        </div>
    </form>
</div> 