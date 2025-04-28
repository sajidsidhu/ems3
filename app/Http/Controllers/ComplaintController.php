<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EmployeeComplainte;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // Method to assign a complaint to a maintenance staff member
    public function assign(Request $request, EmployeeComplainte $complaint)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $complaint->assigned_to = $request->assigned_to;
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint assigned successfully.');
    }

    // Method to mark a complaint as resolved
    public function resolve(Request $request, EmployeeComplainte $complaint)
    {
        $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $complaint->status = 'Resolved';
        $complaint->remarks = $request->remarks;
        $complaint->resolved_by = Auth::id();
        $complaint->resolved_at = now();
        $complaint->save();

        return redirect()->back()->with('success', 'Complaint resolved successfully.');
    }
}
