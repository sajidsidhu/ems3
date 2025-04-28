<?php

namespace App\Livewire;

use App\Models\EmployeeComplainte;
use Livewire\Component;

class AddComplaint extends Component
{
    public $complaint_subject;
    public $complaint;

    protected $rules = [
        'complaint_subject' => 'required|string|max:255',
        'complaint' => 'required|string|max:255',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->validate();

        EmployeeComplainte::create([
            'employee_id' => auth()->id(),
            'complaint_subject' => $this->complaint_subject,
            'complaint' => $this->complaint,
            'status' => 'Pending',
        ]);

        session()->flash('success', 'Complaint added successfully.');
        $this->reset(['complaint_subject', 'complaint']);
    }

    public function render()
    {
        return view('livewire.add-complaint');
    }
}
