<div class="px-6">
    @if(!$showEditForm)
        <h2 class="text-xl font-bold mb-4">Leave Types</h2>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($showEditForm)
        <flux:fieldset>
            <h3 class="text-lg font-semibold mb-4">Edit Leave Type</h3>
            <form wire:submit.prevent="update">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Name</label>
                        <flux:input type="text" id="name" wire:model="name" />
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="default_days" class="block text-sm font-medium mb-1">Default Days</label>
                        <flux:input type="number" id="default_days" wire:model="default_days" />
                        @error('default_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium mb-1">Status</label>
                        <flux:select id="is_active" wire:model="is_active">
                            <flux:select.option value="1">Active</flux:select.option>
                            <flux:select.option value="0">Inactive</flux:select.option>
                        </flux:select>
                        @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label for="description" class="block text-sm font-medium mb-1">Description</label>
                        <flux:textarea id="description" wire:model="description"></flux:textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <flux:button type="button" wire:click="resetForm" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Update</flux:button>
                </div>
            </form>
        </flux:fieldset>
    @endif

    @if(!$showEditForm)
    <table class="mt-4 border-collapse border max-w-full w-full mb-4">
        <thead>
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Description</th>
                <th class="border p-2">Default Days</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypes as $type)
                <tr>
                    <td class="border p-2">{{ $type->name }}</td>
                    <td class="border p-2">{{ $type->description }}</td>
                    <td class="border p-2">{{ $type->default_days }}</td>
                    <td class="border p-2">
                        <flux:badge size="sm" :color="$type->is_active ? 'green' : 'red'" inset="top bottom">
                            {{ $type->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </td>
                    <td class="border p-2">
                        <flux:button wire:click="edit({{ $type->id }})" variant="primary" class="bg-yellow-500 px-2 py-1" icon="pencil-square" size="sm">Edit</flux:button>
                        <flux:button wire:click="delete({{ $type->id }})" variant="danger" class="px-2 py-1" icon="trash" size="sm">Delete</flux:button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $leaveTypes->links() }}
    @endif
</div>