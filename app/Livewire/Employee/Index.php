<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')] 
#[Title('Employees')]
class Index extends Component
{
    use WithPagination;
    public $sortBy = 'id';
    public $sortDirection = 'desc';
    public $query = '';
    
    public function render()
    {
        return view('livewire.employee.index');
    }

    public function delete($id)
    {
        Employee::deleteByUserID($id);
        session()->flash('message', 'Employee deleted successfully.');
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[\Livewire\Attributes\Computed]
    public function employees()
    {
        return Employee::query()
            ->tap(fn ($query) => $this->query ? $query->where('phone', 'like', '%'.$this->query.'%') : $query)
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(5);
    }

    public function edit($id)
    {
        return redirect()->route('employees.add', ['user_id' => $id]);
    }

}
