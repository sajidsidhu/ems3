<div class="max-w-4xl mx-auto p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Assign Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="assign" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Complaint Selection -->
            <flux:select label="Select Complaint" wire:model="selectedComplaint">
                <flux:select.option value="">-- Select Complaint --</flux:select.option>
                @foreach ($complaints as $complaint)
                    <flux:select.option value="{{ $complaint->id }}">{{ $complaint->complaint }}</flux:select.option>
                @endforeach
            </flux:select>

            <!-- Assign To -->
            <flux:select label="Assign To" wire:model="assignedTo">
                <flux:select.option value="">-- Select Staff --</flux:select.option>
                @foreach (App\Models\User::where('role', 'Maintenance Staff')->get() as $user)
                    <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:field>
            <flux:label>Complaint Subject</flux:label>
            <flux:input wire:model="complaint_subject" placeholder="Complaint subject will be displayed here..." disabled />
        </flux:field>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <flux:button type="submit" variant="primary">Assign</flux:button>
        </div>
    </form>
</div>
