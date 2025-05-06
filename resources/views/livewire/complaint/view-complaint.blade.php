<div class="max-w-4xl mx-auto p-6 rounded-lg shadow-md">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">View Complaint</h2>
    </div>

    @if($editMode)
        <form wire:submit.prevent="save" class="space-y-4">
            <flux:field>
                <flux:label>Subject</flux:label>
                <flux:input wire:model="complaint.complaint_subject" />
                @error('complaint.complaint_subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:textarea wire:model="complaint.complaint" rows="4"></flux:textarea>
                @error('complaint.complaint') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>

            <div class="flex justify-end space-x-2">
                <flux:button type="submit" variant="primary">Save Changes</flux:button>
            </div>
        </form>
    @else
        <div class="bg-white rounded-lg p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-600">Subject</h3>
                    <p>{{ $complaint->complaint_subject }}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-600">Status</h3>
                    <p>
                        <flux:badge color="{{ 
                            $complaint->status === 'Resolved' ? 'green' : 
                            ($complaint->status === 'Assigned' ? 'blue' : 'yellow') 
                        }}">
                            {{ $complaint->status }}
                        </flux:badge>
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-600">Created By</h3>
                    <p>{{ $complaint->creator->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-600">Assigned To</h3>
                    <p>{{ $complaint->assignee->name ?? 'Unassigned' }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-600">Created Date</h3>
                    <p>{{ $complaint->created_at->format('Y-m-d H:i') }}</p>
                </div>

                @if($complaint->resolved_at)
                <div>
                    <h3 class="font-semibold text-gray-600">Resolved Date</h3>
                    <p>{{ $complaint->resolved_at }}</p>
                </div>
                @endif
            </div>

            <div class="mt-4">
                <h3 class="font-semibold text-gray-600">Description</h3>
                <p class="whitespace-pre-line">{{ $complaint->complaint }}</p>
            </div>

            @if($complaint->remarks)
            <div class="mt-4">
                <h3 class="font-semibold text-gray-600">Resolution Remarks</h3>
                <p class="whitespace-pre-line">{{ $complaint->remarks }}</p>
            </div>
            @endif
        </div>
    @endif
</div>