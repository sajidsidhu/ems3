<div>
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

    <div class="max-w-7xl mx-auto p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Complaint List</h2>

        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search complaints..." class="border border-gray-300 rounded px-4 py-2 w-full" />
        </div>

        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Complaint ID</th>
                    <th class="border border-gray-300 px-4 py-2">Title</th>
                    <th class="border border-gray-300 px-4 py-2">Description</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Created By</th>
                    <th class="border border-gray-300 px-4 py-2">Assigned To</th>
                    <th class="border border-gray-300 px-4 py-2">Created Date</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            @if ($complaints->count() > 0)
                <tbody>
                    @foreach ($complaints as $complaint)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->complaint_subject }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ Str::limit($complaint->complaint, 50) }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <flux:badge color="{{ 
                                    $complaint->status === 'Resolved' ? 'green' : 
                                    ($complaint->status === 'Assigned' ? 'blue' : 'yellow') 
                                }}">
                                    {{ $complaint->status }}
                                </flux:badge>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->creator->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->assignee->name ?? 'Unassigned' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->created_at->format('Y-m-d') }}</td>
                            <td class="border border-gray-300 px-4 py-2 space-x-2">
                                <flux:button as="a" href="{{ route('complaints.view', $complaint->id) }}"  bg-color="blue" size="sm" icon="eye">
                                    View
                                </flux:button>
                                <flux:button as="a" href="{{ route('complaints.edit', $complaint->id) }}" bg-color="yellow" size="sm" icon="pencil">
                                    Edit
                                </flux:button>
                                <flux:badge as="button" wire:click="confirmDelete({{ $complaint->id }})" variant="solid" color="red" size="sm" icon="trash">
                                    Delete
                                </flux:badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @else
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center py-4">No complaints found.</td>
                    </tr>
                </tbody>
            @endif
        </table>

        @if($complaints->count() > 0)
            <div class="mt-4">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($complaintToDelete)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
            <p class="mb-4">Are you sure you want to delete this complaint? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <flux:badge as="button" wire:click="cancelDelete" variant="ghost" size="sm">
                    Cancel
                </flux:badge>
                <flux:badge as="button" wire:click="delete" variant="solid" color="red" size="sm">
                    Delete
                </flux:badge>
            </div>
        </div>
    </div>
    @endif
</div>
