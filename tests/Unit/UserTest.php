<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->department = Department::factory()->create();
    }

    #[Test]
    public function it_can_create_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'support_team',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'support_team',
        ]);
    }

    #[Test]
    public function it_automatically_generates_employee_id_on_creation()
    {
        $user = User::factory()->create([
            'employee_id' => null,
        ]);

        $this->assertNotNull($user->employee_id);
        $this->assertTrue(Str::isUuid($user->employee_id));
    }

    #[Test]
    public function it_can_check_if_user_is_admin()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $regularUser = User::factory()->create(['role' => 'support_team']);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    #[Test]
    public function it_can_get_bio_details()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'employee_id' => 'test-uuid',
            'position' => 'Developer',
            'email' => 'jane@example.com',
        ]);

        $bioDetails = $user->getBioDetails();

        $this->assertEquals([
            'id' => $user->id,
            'name' => 'Jane Doe',
            'employee_id' => 'test-uuid',
            'department_id' => $user->department_id,
            'position' => 'Developer',
            'email' => 'jane@example.com',
        ], $bioDetails);
    }

    #[Test]
    public function it_has_department_relationship()
    {
        $user = User::factory()->create(['department_id' => $this->department->id]);

        $this->assertInstanceOf(Department::class, $user->department);
        $this->assertEquals($this->department->id, $user->department->id);
    }

    #[Test]
    public function it_casts_attributes_correctly()
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->assertIsBool($user->is_active);
        $this->assertTrue($user->is_active);
    }

    #[Test]
    public function it_hides_sensitive_attributes()
    {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    #[Test]
    public function it_can_be_mass_assigned()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'position' => 'Tester',
            'phone' => '1234567890',
            'role' => 'support_team',
            'is_active' => true,
        ];

        $user = User::create($userData);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('Tester', $user->position);
    }
} 