<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EmployeeComplainte;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ComplaintModuleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_add_a_complaint()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('complaints.add'), [
                'complaint' => 'Test complaint',
                'complaint_subject' => 'Test Subject',
            ])
            ->assertStatus(302)
            ->assertSessionHas('success', 'Complaint added successfully.');

        $this->assertDatabaseHas('employee_complaintes', [
            'employee_id' => $user->id,
            'complaint' => 'Test complaint',
            'status' => 'Pending',
        ]);
    }

    #[Test]
    public function an_hr_or_admin_can_assign_a_complaint()
    {
        $hr = User::factory()->create(['role' => 'HR']);
        $staff = User::factory()->create(['role' => 'Maintenance Staff']);
        $complaint = EmployeeComplainte::factory()->create(['status' => 'Pending']);

        $this->actingAs($hr)
            ->post(route('complaints.assign', $complaint->id), [
                'assigned_to' => $staff->id,
            ])
            ->assertStatus(302)
            ->assertSessionHas('success', 'Complaint assigned successfully.');

        $this->assertDatabaseHas('employee_complaintes', [
            'id' => $complaint->id,
            'assigned_to' => $staff->id,
        ]);
    }

    #[Test]
    public function a_maintenance_staff_can_resolve_a_complaint()
    {
        $staff = User::factory()->create(['role' => 'Maintenance Staff']);
        $complaint = EmployeeComplainte::factory()->create([
            'status' => 'Pending',
            'assigned_to' => $staff->id,
        ]);

        $this->actingAs($staff)
            ->post(route('complaints.resolve', $complaint->id), [
                'remarks' => 'Resolved successfully',
            ])
            ->assertStatus(302)
            ->assertSessionHas('success', 'Complaint resolved successfully.');

        $this->assertDatabaseHas('employee_complaintes', [
            'id' => $complaint->id,
            'status' => 'Resolved',
            'remarks' => 'Resolved successfully',
        ]);
    }
}