
<flux:fieldset>
    <form wire:submit.prevent="saveEmployee" autofill="off">
        <flux:legend class="text-xl font-bold mb-4">{{ $user_id ? 'Edit' : 'Add'}} Employee</flux:legend>
        <flux:input type="hidden" wire:model="user_id"/>
        <flux:input type="hidden" wire:model="e_id"/>
        <div class="space-y-6">
            <div class="grid grid-cols-3 gap-x-4 gap-y-6">
                <flux:input type="text" wire:model="employee_id" label="Employee ID" placeholder="Employee ID"/>
                <flux:input type="text" wire:model="first_name" label="First Name" placeholder="First Name"/>
                <flux:input type="text" wire:model="last_name" label="Last Name" placeholder="Last Name"/>
                <flux:input type="email" wire:model="email" label="Email" placeholder="jhon@gmail.com"/>
                <flux:input type="password" wire:model="password" label="Password" placeholder="*******"/>
                <flux:input type="phone" wire:model="phone" label="Phone" placeholder="0987654321"/>
                <flux:input type="number" wire:model="salary" label="Salary" placeholder="Salary"/>
                <flux:input type="date" wire:model="hire_date" label="Hire Date" placeholder="Hire Date"/>
                <flux:select wire:model="status" label="Account Status">
                    <option value="">Select user status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </flux:select>
                <flux:select wire:model="role" label="Employee Role">
                    <option value="">Select Role</option>
                    <option value="HR Administrator">HR Administrator</option>
                    <option value="Employee">Employee</option>
                    <option value="Maintenance Staff">Maintenance Staff</option>
                </flux:select>
                <flux:select wire:model="position" label="Employee Designation">
                    <option value="">Select Position</option>
                    <option value="Mill Manager">Mill Manager</option>
                    <option value="Production Supervisor">Production Supervisor</option>
                    <option value="Quality Control Officer">Quality Control Officer</option>
                    <option value="Machine Operator">Machine Operator</option>
                    <option value="Maintenance Technician">Maintenance Technician</option>
                    <option value="Logistics Coordinator">Logistics Coordinator</option>
                </flux:select>

                @if($this->user_id)
                    <flux:button type="submit" class="bg-blue-500 text-white px-4 py-2">Update</flux:button>
                @else
                    <flux:button type="submit" class="bg-green-500 text-white px-4 py-2">Save</flux:button>
                @endif
            </div>
        </div>
    </form>
</flux:fieldset>
