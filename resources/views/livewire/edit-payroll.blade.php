<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Payroll</h2>

                    <form wire:submit="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Employee Selection -->
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee</label>
                                <select wire:model.live="employee_id" id="employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Payroll Month -->
                            <div>
                                <label for="payroll_month" class="block text-sm font-medium text-gray-700">Payroll Month</label>
                                <input wire:model.live="payroll_month" type="month" id="payroll_month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('payroll_month') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Basic Salary -->
                            <div>
                                <label for="basic_salary" class="block text-sm font-medium text-gray-700">Basic Salary</label>
                                <input wire:model="basic_salary" type="number" step="0.01" id="basic_salary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                @error('basic_salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Working Days Summary -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Working Days Summary</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Total Working Days</label>
                                        <input wire:model="total_working_days" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Present Days</label>
                                        <input wire:model="total_present_days" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Absent Days</label>
                                        <input wire:model="total_absent_days" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Leaves Taken</label>
                                        <input wire:model="total_leaves_taken" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Additional Information</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Late Arrivals</label>
                                        <input wire:model="total_late_arrivals" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Working Hours</label>
                                        <input wire:model="total_working_hours" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Total Deductions</label>
                                        <input wire:model="total_deductions" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Total Additions</label>
                                        <input wire:model="total_additions" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Net Salary -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="net_salary" class="block text-sm font-medium text-gray-700">Net Salary</label>
                                <input type="number" step="0.01" name="net_salary" id="net_salary" wire:model="net_salary" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                                @error('net_salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="col-span-6">
                                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                <textarea name="remarks" id="remarks" wire:model="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Deductions and Additions -->
                            <div class="col-span-6">
                                <div class="bg-white shadow sm:rounded-lg">
                                    <div class="px-4 py-5 sm:p-6">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Deductions and Additions</h3>
                                        <div class="mt-5">
                                            <form wire:submit.prevent="addDetail" class="space-y-4">
                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                                        <select wire:model="newDetail.type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                            <option value="">Select Type</option>
                                                            <option value="addition">Addition</option>
                                                            <option value="deduction">Deduction</option>
                                                        </select>
                                                        @error('newDetail.type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                                        <input type="number" step="0.01" wire:model="newDetail.amount" id="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        @error('newDetail.amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                                    <input type="text" wire:model="newDetail.description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    @error('newDetail.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label for="detail_remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                                    <input type="text" wire:model="newDetail.remarks" id="detail_remarks" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    @error('newDetail.remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Add Detail
                                                    </button>
                                                </div>
                                            </form>

                                            @if(count($details) > 0)
                                                <div class="mt-6">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                                                <th scope="col" class="relative px-6 py-3">
                                                                    <span class="sr-only">Actions</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($details as $index => $detail)
                                                                <tr>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        {{ ucfirst($detail['type']) }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $detail['description'] }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ number_format($detail['amount'], 2) }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $detail['remarks'] }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                        <button wire:click="removeDetail({{ $index }})" class="text-red-600 hover:text-red-900">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('payroll.show', $payroll) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit" wire:click="save" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Payroll
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 