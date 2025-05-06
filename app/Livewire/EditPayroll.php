<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\EmployeeAttendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditPayroll extends Component
{
    public Payroll $payroll;
    public $employee_id;
    public $payroll_month;
    public $basic_salary;
    public $total_working_days = 0;
    public $total_present_days = 0;
    public $total_absent_days = 0;
    public $total_late_arrivals = 0;
    public $total_late_minutes = 0;
    public $total_leaves_taken = 0;
    public $total_working_hours = 0;
    public $total_deductions = 0;
    public $total_additions = 0;
    public $net_salary = 0;
    public $remarks;
    public $details = [];
    public $newDetail = [
        'type' => '',
        'description' => '',
        'amount' => 0,
        'remarks' => ''
    ];

    public function mount(Payroll $payroll)
    {
        $this->payroll = $payroll;
        $this->employee_id = $payroll->employee_id;
        $this->payroll_month = $payroll->payroll_month->format('Y-m');
        $this->basic_salary = $payroll->basic_salary;
        $this->total_working_days = $payroll->total_working_days;
        $this->total_present_days = $payroll->total_present_days;
        $this->total_absent_days = $payroll->total_absent_days;
        $this->total_late_arrivals = $payroll->total_late_arrivals;
        $this->total_late_minutes = $payroll->total_late_minutes;
        $this->total_leaves_taken = $payroll->total_leaves_taken;
        $this->total_working_hours = $payroll->total_working_hours;
        $this->total_deductions = $payroll->total_deductions;
        $this->total_additions = $payroll->total_additions;
        $this->net_salary = $payroll->net_salary;
        $this->remarks = $payroll->remarks;
        
        // Load existing details
        $this->details = $payroll->details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'type' => $detail->type,
                'description' => $detail->description,
                'amount' => $detail->amount,
                'remarks' => $detail->remarks
            ];
        })->toArray();
    }

    public function updatedEmployeeId()
    {
        if ($this->employee_id) {
            $employee = Employee::find($this->employee_id);
            $this->basic_salary = $employee->salary;
            $this->calculateAttendance();
        }
    }

    public function updatedPayrollMonth()
    {
        if ($this->employee_id) {
            $this->calculateAttendance();
        }
    }

    protected function calculateAttendance()
    {
        $startDate = Carbon::parse($this->payroll_month)->startOfMonth();
        $endDate = Carbon::parse($this->payroll_month)->endOfMonth();

        // Calculate working days (excluding weekends)
        $this->total_working_days = $startDate->copy()->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $endDate);

        // Get attendance records
        $attendances = EmployeeAttendance::where('employee_id', $this->employee_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $this->total_present_days = $attendances->where('status', 'Present')->count();
        $this->total_absent_days = $attendances->where('status', 'Absent')->count();
        $this->total_late_arrivals = $attendances->whereNotNull('clock_in')
            ->filter(function ($attendance) {
                return Carbon::parse($attendance->clock_in)->format('H:i') > '08:00';
            })->count();
        
        // Calculate total late minutes
        $this->total_late_minutes = $attendances->sum('late_minutes');

        // Calculate working hours
        $this->total_working_hours = $attendances->sum('working_hours');

        // Get approved leaves
        $this->total_leaves_taken = LeaveRequest::where('employee_id', $this->employee_id)
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->sum('days_requested');

        $this->calculateDeductions();
    }

    protected function calculateDeductions()
    {
        // Calculate deductions based on attendance
        $dailyRate = $this->basic_salary / $this->total_working_days;
        
        // Deduct for absences
        $absenceDeduction = $this->total_absent_days * $dailyRate;
        
        // Deduct for late minutes (e.g., 1 minute late = 1/480 of daily rate)
        $lateDeduction = ($this->total_late_minutes / 480) * $dailyRate;
        
        $this->total_deductions = $absenceDeduction + $lateDeduction;
        
        // Calculate net salary
        $this->net_salary = $this->basic_salary - $this->total_deductions + $this->total_additions;
    }

    public function addDetail()
    {
        $this->validate([
            'newDetail.type' => 'required|in:addition,deduction',
            'newDetail.description' => 'required|string|max:255',
            'newDetail.amount' => 'required|numeric|min:0',
            'newDetail.remarks' => 'nullable|string|max:255'
        ]);

        $this->details[] = $this->newDetail;
        
        // Update totals
        if ($this->newDetail['type'] === 'addition') {
            $this->total_additions += $this->newDetail['amount'];
        } else {
            $this->total_deductions += $this->newDetail['amount'];
        }
        
        $this->calculateNetSalary();
        
        // Reset new detail
        $this->newDetail = [
            'type' => '',
            'description' => '',
            'amount' => 0,
            'remarks' => ''
        ];
    }

    public function removeDetail($index)
    {
        $detail = $this->details[$index];
        
        // Update totals
        if ($detail['type'] === 'addition') {
            $this->total_additions -= $detail['amount'];
        } else {
            $this->total_deductions -= $detail['amount'];
        }
        
        unset($this->details[$index]);
        $this->details = array_values($this->details);
        
        $this->calculateNetSalary();
    }

    protected function calculateNetSalary()
    {
        $this->net_salary = $this->basic_salary + $this->total_additions - $this->total_deductions;
    }

    public function save()
    {
        $this->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_month' => 'required|date_format:Y-m',
            'basic_salary' => 'required|numeric|min:0',
            'total_working_days' => 'required|integer|min:0',
            'total_present_days' => 'required|integer|min:0',
            'total_absent_days' => 'required|integer|min:0',
            'total_late_arrivals' => 'required|integer|min:0',
            'total_late_minutes' => 'required|integer|min:0',
            'total_leaves_taken' => 'required|integer|min:0',
            'total_working_hours' => 'required|numeric|min:0',
            'total_deductions' => 'required|numeric|min:0',
            'total_additions' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
        ]);

        $this->payroll->update([
            'employee_id' => $this->employee_id,
            'payroll_month' => Carbon::parse($this->payroll_month)->startOfMonth(),
            'basic_salary' => $this->basic_salary,
            'total_working_days' => $this->total_working_days,
            'total_present_days' => $this->total_present_days,
            'total_absent_days' => $this->total_absent_days,
            'total_late_arrivals' => $this->total_late_arrivals,
            'total_late_minutes' => $this->total_late_minutes,
            'total_leaves_taken' => $this->total_leaves_taken,
            'total_working_hours' => $this->total_working_hours,
            'total_deductions' => $this->total_deductions,
            'total_additions' => $this->total_additions,
            'net_salary' => $this->net_salary,
            'remarks' => $this->remarks,
            'updated_by' => Auth::id(),
        ]);

        // Update payroll details
        $this->payroll->details()->delete(); // Remove existing details
        foreach ($this->details as $detail) {
            $this->payroll->details()->create([
                'type' => $detail['type'],
                'description' => $detail['description'],
                'amount' => $detail['amount'],
                'remarks' => $detail['remarks']
            ]);
        }

        session()->flash('message', 'Payroll updated successfully.');
        return redirect()->route('payroll.show', $this->payroll);
    }

    public function render()
    {
        return view('livewire.edit-payroll', [
            'employees' => Employee::where('status', 'Active')->get()
        ]);
    }
} 