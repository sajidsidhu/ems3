<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Generate Payroll</h2>

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
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Net Salary</label>
                                <input wire:model="net_salary" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                @error('net_salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="col-span-2">
                                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                <textarea wire:model="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('payroll.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Generate Payroll
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 