<?php
namespace App\Livewire\Leave;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveType;

class LeaveTypeManager extends Component
{
    use WithPagination;

    public $leaveTypeId;
    public $name;
    public $description;
    public $default_days;
    public $is_active = true;
    public $showEditForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'default_days' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        LeaveType::updateOrCreate(
            ['id' => $this->leaveTypeId],
            [
                'name' => $this->name,
                'description' => $this->description,
                'default_days' => $this->default_days,
                'is_active' => $this->is_active
            ]
        );

        $this->resetForm();
        session()->flash('success', 'Leave type saved successfully.');
    }

    public function edit($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $this->leaveTypeId = $leaveType->id;
        $this->name = $leaveType->name;
        $this->description = $leaveType->description;
        $this->default_days = $leaveType->default_days;
        $this->is_active = $leaveType->is_active;
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate();

        LeaveType::findOrFail($this->leaveTypeId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'default_days' => $this->default_days,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Leave type updated successfully.');

        $this->resetForm(); // Reset the form
        $this->emit('refreshComponent'); // Emit an event to refresh the component
    }

    public function delete($id)
    {
        LeaveType::findOrFail($id)->delete();
        session()->flash('success', 'Leave type deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['leaveTypeId', 'name', 'description', 'default_days', 'is_active', 'showEditForm']);
    }

    public function render()
    {
        return view('livewire.leave.leave-type-manager', [
            'leaveTypes' => LeaveType::paginate(10), // Ensure pagination is used
        ]);
    }
}