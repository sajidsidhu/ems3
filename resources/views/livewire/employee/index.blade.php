
<div class="px-6">
    <h2 class="text-xl font-bold mb-4">Employee Management</h2>
    <flux:input icon-trailing="magnifying-glass" type="text"  wire:model.live.debounce.500ms="query" placeholder="Search by phone" class="max-w-sm float-right mb-5"/>   
    <table class="mt-4 border-collapse border max-w-full w-full mb-4">
        <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">Designation</th>
            <th class="border p-2">Salary</th>
            <th class="border p-2">Role</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Actions</th>
        </tr>
        @foreach($this->employees() as $employee)
            <tr>
                <td class="border p-2">{{ $employee->employee_id }}</td>
                <td class="border p-2">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td class="border p-2">{{ $employee->position }}</td>
                <td class="border p-2">${{ number_format($employee->salary, 2) }}</td>
                <td class="border p-2">{{ $employee->user->role }}</td>
                <td class="border p-2">
                    <flux:badge size="sm" :color="$employee->user->status == 'Active' ? 'green': 'red'" inset="top bottom">{{ $employee->user->status }}</flux:badge>
                </td>
                    
                <td class="border p-2">
                    <flux:button wire:click="edit({{ $employee->user_id }})" variant="primary" class="bg-yellow-500 px-2 py-1" icon="pencil-square" size="sm"></flux:button>
                    <flux:button wire:click="delete({{ $employee->user_id }})" variant="danger" class="px-2 py-1" icon="trash"  size="sm"></flux:button>
                </td>
            </tr>
        @endforeach
    </table>
    
    {{ $this->employees()->links() }}
</div>

