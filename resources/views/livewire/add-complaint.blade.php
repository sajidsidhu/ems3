<div class="max-w-4xl mx-auto p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Add Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <!-- Complaint Subject Input -->
            <flux:field>
                <flux:label>Complaint Subject</flux:label>
                <flux:input wire:model="complaint_subject" placeholder="Enter the subject of your complaint..." />
            </flux:field>

            <!-- Complaint Input -->
            <flux:field>
                <flux:label>Complaint</flux:label>
                <flux:textarea wire:model="complaint" placeholder="Describe your complaint..."></flux:textarea>
            </flux:field>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <flux:button type="submit" variant="primary">Submit</flux:button>
        </div>
    </form>
</div>
