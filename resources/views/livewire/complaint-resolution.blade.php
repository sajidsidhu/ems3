<div class="max-w-4xl mx-auto p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Resolve Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="resolve" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Complaint Selection -->
            <flux:select label="Select Complaint" wire:model="selectedComplaint">
                <flux:select.option value="">-- Select Complaint --</flux:select.option>
                @foreach ($complaints as $complaint)
                    <flux:select.option value="{{ $complaint->id }}">{{ $complaint->complaint }}</flux:select.option>
                @endforeach
            </flux:select>

            <!-- Remarks -->
            <flux:field>
                <flux:label>Remarks</flux:label>
                <flux:textarea wire:model="remarks" placeholder="Additional notes..."></flux:textarea>
            </flux:field>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <flux:button type="submit" variant="primary">Resolve</flux:button>
        </div>
    </form>
</div>
