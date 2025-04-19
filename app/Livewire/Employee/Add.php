<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

#[Layout('components.layouts.app')] 
#[Title('Add Employee')]
class Add extends Component
{

    public $user_id, $e_id, $status, $phone, $email, $role, $password, $first_name, $last_name, $employee_id, $salary, $hire_date, $position;
    
    public function mount($user_id=null) 
    {
        if($user_id){
            $user = User::getByID($user_id);
            $employee = Employee::getByUserID($user_id);
            $this->email = $user->email;
            $this->role = $user->role;
            $this->status = $user->status;
            $this->phone = $employee->phone;
            $this->first_name = $employee->first_name;
            $this->last_name = $employee->last_name;
            $this->employee_id = $employee->employee_id;
            $this->salary = $employee->salary;
            $this->hire_date = $employee->hire_date;
            $this->position = $employee->position;
            $this->user_id = $user->id;
            $this->e_id = $employee->id;
        }
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:employees,phone,'.$this->e_id,
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user_id)],
            'role' => 'required|string|in:HR Administrator,Employee,Maintenance Staff',
            'status' => 'required|string|in:Active,Inactive',
            'password' => 'required_without:user_id|string|min:8',
            'employee_id' => ['required', 'string', Rule::unique('employees', 'employee_id')->ignore($this->e_id)],
            'salary' => 'required|numeric',
            'hire_date' => 'required|date',
            'position' => 'required|string',
        ];
    }

    protected function messages():array
    {
        return [
            'phone.unique' => 'The phone has already been taken.',
            'email.unique' => 'The email has already been taken.',
            'employee_id.unique' => 'The employee id has already been taken.',
            'role.in' => 'The selected role is invalid.',
            'status.in' => 'The selected status is invalid.',

        ];
    }

    public function saveEmployee()
    {
        $this->validate();

        $user_data = [
            'name' => $this->first_name .' '. $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
        if($this->password){
            $user_data['password'] = Hash::make($this->password);
        }

        $employee_data = [
            'user_id' => $this->user_id,
            'employee_id' => $this->employee_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'position' => $this->position,
            'salary' => $this->salary,
            'hire_date' => $this->hire_date,
            'status' => $this->status,
        ];

        if($this->user_id){
            User::updateUser($this->user_id, $user_data);
        }else{
            $user = User::add($user_data);
            $this->user_id = $user->id;
        }
        
        if($this->e_id){
            Employee::updateEmployee($this->e_id, $employee_data);
            session()->flash('message', 'Employee updated successfully.'); 
        }else{
            Employee::add($employee_data);
            session()->flash('message', 'Employee added successfully.');
        }
        
        return redirect()->route('employees.list');
    }

    public function render()
    {
        return view('livewire.employee.add');
    }
}
