<div>
    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employee..." class="input input-bordered">
        <input type="date" wire:model.live="dateFilter" class="input input-bordered">
        <select wire:model.live="perPage" class="select select-bordered">
            <option>10</option>
            <option>25</option>
            <option>50</option>
        </select>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary">Add Attendance</a>
    </div>

    <table class="mt-4 border-collapse border max-w-full w-full mb-4">
        <thead>
            <tr>
                <th class="border p-2">Employee</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Clock In</th>
                <th class="border p-2">Clock Out</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td class="border p-2">{{ $attendance->employee->first_name .' '. $attendance->employee->last_name }}</td>
                <td class="border p-2">{{ $attendance->date->format('d M Y') }}</td>
                <td class="border p-2">{{ $attendance->clock_in?->format('H:i') ?? '-' }}</td>
                <td class="border p-2">{{ $attendance->clock_out?->format('H:i') ?? '-' }}</td>
                <td class="border p-2">
                    <flux:badge size="sm" :color="match($attendance->status) {
                        'Present' => 'green',
                        'Absent' => 'red',
                        'Half Day' => 'orange'
                        }" inset="top bottom">
                        {{ $attendance->status }}</flux:badge>
                </td>
                <td  class="border p-2 flex gap-2">
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-info">Edit</a>
                    <livewire:attendance.delete-attendance :attendance="$attendance" :key="$attendance->id"/>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $attendances->links() }}
</div>