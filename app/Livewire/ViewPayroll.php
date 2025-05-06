<?php

namespace App\Livewire;

use App\Models\Payroll;
use Livewire\Component;

class ViewPayroll extends Component
{
    public Payroll $payroll;

    public function mount(Payroll $payroll)
    {
        $this->payroll = $payroll->load(['employee', 'details', 'approvedBy', 'createdBy']);
    }

    public function approve()
    {
        if ($this->payroll->status === 'draft') {
            $this->payroll->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_by' => auth()->id()
            ]);

            session()->flash('message', 'Payroll approved successfully.');
        }
    }

    public function markAsPaid()
    {
        if ($this->payroll->status === 'approved') {
            $this->payroll->update([
                'status' => 'paid',
                'paid_at' => now(),
                'updated_by' => auth()->id()
            ]);

            session()->flash('message', 'Payroll marked as paid successfully.');
        }
    }

    public function render()
    {
        return view('livewire.view-payroll');
    }
} 