<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;
    private Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'support_team']);
        $this->category = Category::factory()->create();
        $this->department = Department::factory()->create();
    }
    
    #[Test]
    public function it_shows_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[Test]
    public function it_shows_register_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    #[Test]
    public function it_can_register_a_new_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => $this->department->id,
            'position' => 'Developer',
            'phone' => '1234567890',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Developer',
            'role' => 'support_team',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function it_validates_required_fields_for_registration()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'department', 'position', 'phone']);
    }

    #[Test]
    public function it_validates_email_uniqueness_for_registration()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => $this->department->id,
            'position' => 'Developer',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function it_validates_password_confirmation_for_registration()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
            'department' => $this->department->id,
            'position' => 'Developer',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    #[Test]
    public function it_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    #[Test]
    public function it_validates_required_fields_for_login()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    #[Test]
    public function it_rejects_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[Test]
    public function it_can_logout_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    #[Test]
    public function it_redirects_authenticated_user_away_from_login()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function it_redirects_authenticated_user_away_from_register()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/register');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function it_automatically_generates_employee_id_on_registration()
    {
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => $this->department->id,
            'position' => 'Manager',
            'phone' => '1234567890',
        ];

        $this->post('/register', $userData);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user->employee_id);
        $this->assertTrue(strlen($user->employee_id) > 0);
    }

    #[Test]
    public function it_sets_default_role_to_support_team_on_registration()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => $this->department->id,
            'position' => 'Tester',
            'phone' => '1234567890',
        ];

        $this->post('/register', $userData);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertEquals('support_team', $user->role);
    }

    #[Test]
    public function it_sets_user_as_active_on_registration()
    {
        $userData = [
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => $this->department->id,
            'position' => 'Developer',
            'phone' => '1234567890',
        ];

        $this->post('/register', $userData);

        $user = User::where('email', 'active@example.com')->first();
        $this->assertTrue($user->is_active);
    }
} 