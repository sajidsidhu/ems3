<?php

namespace App\Livewire;

use App\Models\Payroll;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollList extends Component
{
    use WithPagination;

    public $search = '';
    public $month = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'month' => ['except' => ''],
        'status' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Payroll::query()
            ->with(['employee', 'approvedBy'])
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->month, function ($query) {
                $query->whereYear('payroll_month', date('Y', strtotime($this->month)))
                    ->whereMonth('payroll_month', date('m', strtotime($this->month)));
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest();

        return view('livewire.payroll-list', [
            'payrolls' => $query->paginate(10)
        ]);
    }
} 