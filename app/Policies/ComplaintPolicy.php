<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EmployeeComplainte;

class ComplaintPolicy
{
    /**
     * Determine if the user can assign complaints.
     */
    public function assign(User $user)
    {
        return $user->role === 'HR' || $user->role === 'Admin';
    }

    /**
     * Determine if the user can resolve complaints.
     */
    public function resolve(User $user, EmployeeComplainte $complaint)
    {
        return $user->role === 'Maintenance Staff' && $complaint->assigned_to === $user->id;
    }
}
