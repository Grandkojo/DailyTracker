<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Activity;
use App\Models\User;
use App\Models\Category;
use App\Models\ActivityUpdate;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private Category $category;
    private Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create(['role' => 'support_team']);
        $this->category = Category::factory()->create();
        $this->department = Department::factory()->create();
    }

    #[Test]
    public function it_shows_reports_index_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas('activities');
        $response->assertViewHas('activityUpdates');
        $response->assertViewHas('stats');
        $response->assertViewHas('teamMembers');
        $response->assertViewHas('categories');
    }

    #[Test]
    public function it_filters_reports_by_date_range()
    {
        $this->actingAs($this->admin);

        // Create activities in different date ranges
        Activity::factory()->create([
            'activity_date' => '2024-01-10',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
        ]);
        Activity::factory()->create([
            'activity_date' => '2024-01-15',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
        ]);
        Activity::factory()->create([
            'activity_date' => '2024-01-20',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->get('/admin/reports?start_date=2024-01-12&end_date=2024-01-18');

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        $this->assertEquals(1, $activities->count());
    }

    #[Test]
    public function it_filters_reports_by_user()
    {
        $this->actingAs($this->admin);

        $user2 = User::factory()->create();

        Activity::factory()->create([
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'assigned_to' => $user2->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get("/admin/reports?user_id={$this->user->id}");

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        $this->assertEquals(1, $activities->count());
    }

    #[Test]
    public function it_filters_reports_by_category()
    {
        $this->actingAs($this->admin);

        $category2 = Category::factory()->create();

        Activity::factory()->create([
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'category_id' => $category2->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get("/admin/reports?category={$this->category->category_id}");

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        $this->assertEquals(2, $activities->count());
    }

    #[Test]
    public function it_filters_reports_by_status()
    {
        $this->actingAs($this->admin);

        Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'done',
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports?status=pending');

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        $this->assertEquals(2, $activities->count());
    }

    #[Test]
    public function it_shows_activity_report()
    {
        $this->actingAs($this->admin);

        Activity::factory()->count(3)->create([
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/activity');

        $response->assertStatus(200);
        $response->assertViewIs('reports.activity');
        $response->assertViewHas('activities');
        $response->assertViewHas('activitiesByDate');
        $response->assertViewHas('teamMembers');
    }

    #[Test]
    public function it_filters_activity_report_by_user()
    {
        $this->actingAs($this->admin);

        $user2 = User::factory()->create();

        Activity::factory()->create([
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'assigned_to' => $user2->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get("/admin/reports/activity?user_id={$this->user->id}");

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        $this->assertEquals(1, $activities->count());
    }

    #[Test]
    public function it_shows_performance_report()
    {
        $this->actingAs($this->admin);

        // Create activities with different statuses
        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/performance');

        $response->assertStatus(200);
        $response->assertViewIs('reports.performance');
        $response->assertViewHas('users');
        $response->assertViewHas('statusDistribution');
        $response->assertViewHas('departmentPerformance');
    }

    #[Test]
    public function it_calculates_performance_metrics_correctly()
    {
        $this->actingAs($this->admin);

        // Create user with department and all required fields
        $user = User::factory()->create([
            'department_id' => $this->department->id,
            'role' => 'support_team',
            'is_active' => true,
        ]);

        // Create activities with different statuses
        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'assigned_to' => $user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/performance');

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        $userData = $users->firstWhere(fn($u) => $u['user']->id === $user->id);
        $this->assertNotNull($userData, 'User not found in report');
        $this->assertEquals(3, $userData['total_assigned']);
        $this->assertEquals(2, $userData['completed']);
        $this->assertEquals(1, $userData['pending']);
        $this->assertEquals(66.67, $userData['completion_rate']);
    }

    #[Test]
    public function it_shows_department_performance()
    {
        $this->actingAs($this->admin);

        $user1 = User::factory()->create([
            'department_id' => $this->department->id,
            'role' => 'support_team',
        ]);
        $user2 = User::factory()->create([
            'department_id' => $this->department->id,
            'role' => 'support_team',
        ]);

        // Create activities for department users
        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $user1->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'assigned_to' => $user2->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/performance');

        $response->assertStatus(200);
        $departmentPerformance = $response->viewData('departmentPerformance');
        // Only check the department created in this test
        $dept = $departmentPerformance->firstWhere(fn($d) => $d['department'] === $this->department->name);
        $this->assertNotNull($dept, 'Department not found in report');
        $this->assertEquals($this->department->name, $dept['department']);
        $this->assertEquals(50.0, $dept['completion_rate']);
    }

    #[Test]
    public function it_exports_performance_report()
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'department_id' => $this->department->id,
            'role' => 'support_team',
        ]);

        Activity::factory()->create([
            'status' => 'done',
            'assigned_to' => $user->id,
            'category_id' => $this->category->category_id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/export-performance');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=performance_report_' . now()->startOfMonth()->format('Y-m-d') . '_to_' . now()->endOfMonth()->format('Y-m-d') . '.csv');
    }

    #[Test]
    public function it_exports_general_report()
    {
        $this->actingAs($this->admin);

        Activity::factory()->create([
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $expectedFilename = 'attachment; filename=activity_report_' . now()->startOfMonth()->format('Y-m-d') . '_to_' . now()->endOfMonth()->format('Y-m-d') . '.csv';
        $response->assertHeader('Content-Disposition', $expectedFilename);
    }

    #[Test]
    public function it_requires_admin_access_for_reports()
    {
        $this->actingAs($this->user);

        $response = $this->get('/admin/reports');
        $response->assertStatus(403);

        $response = $this->get('/admin/reports/activity');
        $response->assertStatus(403);

        $response = $this->get('/admin/reports/performance');
        $response->assertStatus(403);
    }

    #[Test]
    public function it_requires_authentication_for_reports()
    {
        $response = $this->get('/admin/reports');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/reports/activity');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/reports/performance');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function it_calculates_report_statistics()
    {
        $this->actingAs($this->admin);

        // Create activities with different statuses
        Activity::factory()->create([
            'status' => 'done',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
            'activity_date' => now()->format('Y-m-d'),
        ]);
        Activity::factory()->create([
            'status' => 'cancelled',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user->id,
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/admin/reports');

        $response->assertStatus(200);
        $stats = $response->viewData('stats');
        
        $this->assertEquals(3, $stats['total_activities']);
        $this->assertEquals(1, $stats['completed_activities']);
        $this->assertEquals(1, $stats['pending_activities']);
        $this->assertEquals(1, $stats['cancelled_activities']);
    }

    
} 