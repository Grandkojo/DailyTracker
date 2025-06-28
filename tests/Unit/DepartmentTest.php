<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function it_can_create_a_department()
    {
        $department = Department::factory()->create([
            'name' => 'Engineering',
        ]);

        $this->assertDatabaseHas('departments', [
            'name' => 'Engineering',
        ]);
    }


    #[Test]
    public function it_can_be_mass_assigned()
    {
        $departmentData = [
            'name' => 'Marketing',
        ];

        $department = Department::create($departmentData);

        $this->assertEquals('Marketing', $department->name);
    }

    #[Test]
    public function it_can_be_updated()
    {
        $department = Department::factory()->create([
            'name' => 'Old Department',
        ]);

        $department->update([
            'name' => 'New Department',
        ]);

        $this->assertEquals('New Department', $department->fresh()->name);
    }

    #[Test]
    public function it_can_be_deleted()
    {
        $department = Department::factory()->create();

        $departmentId = $department->id;
        $department->delete();

        $this->assertDatabaseMissing('departments', [
            'id' => $departmentId,
        ]);
    }

    #[Test]
    public function it_can_be_listed_with_users_count()
    {
        $department1 = Department::factory()->create();
        $department2 = Department::factory()->create();

        User::factory()->count(2)->create(['department_id' => $department1->id]);
        User::factory()->count(1)->create(['department_id' => $department2->id]);

        $departmentsWithCount = Department::withCount('users')->get();

        $department1WithCount = $departmentsWithCount->where('id', $department1->id)->first();
        $department2WithCount = $departmentsWithCount->where('id', $department2->id)->first();

        $this->assertEquals(2, $department1WithCount->users_count);
        $this->assertEquals(1, $department2WithCount->users_count);
    }

    #[Test]
    public function it_can_have_users_with_different_roles()
    {
        $department = Department::factory()->create();

        User::factory()->create([
            'department_id' => $department->id,
            'role' => 'admin',
        ]);

        User::factory()->create([
            'department_id' => $department->id,
            'role' => 'support_team',
        ]);

        $this->assertEquals(2, $department->users->count());
        $this->assertTrue($department->users->contains('role', 'admin'));
        $this->assertTrue($department->users->contains('role', 'support_team'));
    }

    #[Test]
    public function it_can_be_retrieved_by_name()
    {
        $department = Department::factory()->create([
            'name' => 'Human Resources',
        ]);

        $retrievedDepartment = Department::where('name', 'Human Resources')->first();

        $this->assertEquals($department->id, $retrievedDepartment->id);
        $this->assertEquals('Human Resources', $retrievedDepartment->name);
    }

    #[Test]
    public function it_can_be_ordered_by_name()
    {
        Department::factory()->create(['name' => 'Zebra Department']);
        Department::factory()->create(['name' => 'Alpha Department']);
        Department::factory()->create(['name' => 'Beta Department']);

        $orderedDepartments = Department::orderBy('name')->get();

        $this->assertEquals('Alpha Department', $orderedDepartments->first()->name);
        $this->assertEquals('Zebra Department', $orderedDepartments->last()->name);
    }
} 