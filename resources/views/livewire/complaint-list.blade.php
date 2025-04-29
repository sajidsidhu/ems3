<div>
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
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->status }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->creator->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->assignee->name ?? 'Unassigned' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $complaint->created_at->format('Y-m-d') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button class="text-blue-500 hover:underline" wire:click="viewComplaint({{ $complaint->id }})">View</button>
                                @if ($complaint->employee_id === auth()->id())
                                    <button class="text-green-500 hover:underline" wire:click="editComplaint({{ $complaint->id }})">Edit</button>
                                    <button class="text-red-500 hover:underline" wire:click="deleteComplaint({{ $complaint->id }})">Delete</button>
                                @endif
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

    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h3 class="text-xl font-semibold mb-4">Complaint Details</h3>

                <p><strong>Title:</strong> {{ $selectedComplaint->complaint_subject }}</p>
                <p><strong>Description:</strong> {{ $selectedComplaint->complaint }}</p>
                <p><strong>Status:</strong> {{ $selectedComplaint->status }}</p>
                <p><strong>Created By:</strong> {{ $selectedComplaint->creator->name ?? 'N/A' }}</p>
                <p><strong>Assigned To:</strong> {{ $selectedComplaint->assignee->name ?? 'Unassigned' }}</p>
                <p><strong>Created Date:</strong> {{ $selectedComplaint->created_at->format('Y-m-d') }}</p>

                @if (auth()->user()->role === 'HR')
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="assignComplaint({{ $selectedComplaint->id }})">Assign</button>
                    <button class="bg-green-500 text-white px-4 py-2 rounded" wire:click="resolveComplaint({{ $selectedComplaint->id }})">Mark as Resolved</button>
                @endif

                <button class="bg-gray-500 text-white px-4 py-2 rounded mt-4" wire:click="closeModal">Close</button>
            </div>
        </div>
    @endif

    @if ($showAssignModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h3 class="text-xl font-semibold mb-4">Assign Complaint</h3>

                <p><strong>Complaint:</strong> {{ $selectedComplaint->complaint_subject }}</p>

                <div class="mt-4">
                    <label for="assignee" class="block text-sm font-medium text-gray-700">Assign To:</label>
                    <select id="assignee" wire:model="assigneeId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Select Staff --</option>
                        @foreach (App\Models\User::where('role', 'Maintenance Staff')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button class="bg-gray-500 text-white px-4 py-2 rounded" wire:click="closeModal">Cancel</button>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="assignToUser">Assign</button>
                </div>
            </div>
        </div>
    @endif
</div>
