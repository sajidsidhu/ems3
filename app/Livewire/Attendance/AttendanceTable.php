<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeeAttendance;

class AttendanceTable extends Component
{
    
    use WithPagination;

    public $search = '';
    public $dateFilter;
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'perPage' => ['except' => 10]
    ];

    public function render()
    {
        return view('livewire.attendance.attendance-table', [
            'attendances' => EmployeeAttendance::with(['employee', 'createdBy'])
                ->when($this->search, fn($q) => $q->whereHas('employee', fn($q) => 
                    $q->where('first_name', 'like', "%{$this->search}%")
                ))
                ->when($this->dateFilter, fn($q) => $q->whereDate('date', $this->dateFilter))
                ->latest()
                ->paginate($this->perPage)
        ]);
    }
}