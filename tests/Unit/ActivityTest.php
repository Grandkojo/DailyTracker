<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Activity;
use App\Models\User;
use App\Models\Category;
use App\Models\ActivityUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private User $user1;
    private User $user2;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users and category that will be used across tests
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    #[Test]
    public function it_can_create_an_activity()
    {
        $activity = Activity::factory()->create([
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'pending',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user1->id,
            'created_by' => $this->user2->id,
        ]);

        $this->assertDatabaseHas('activities', [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function it_has_creator_relationship()
    {
        $activity = Activity::factory()->create([
            'created_by' => $this->user1->id,
            'category_id' => $this->category->category_id,
        ]);

        $this->assertInstanceOf(User::class, $activity->creator);
        $this->assertEquals($this->user1->id, $activity->creator->id);
    }

    #[Test]
    public function it_has_assignee_relationship()
    {
        $activity = Activity::factory()->create([
            'assigned_to' => $this->user1->id,
            'category_id' => $this->category->category_id,
        ]);

        $this->assertInstanceOf(User::class, $activity->assignee);
        $this->assertEquals($this->user1->id, $activity->assignee->id);
    }

    #[Test]
    public function it_has_category_relationship()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);

        $this->assertInstanceOf(Category::class, $activity->category);
        $this->assertEquals($this->category->category_id, $activity->category->category_id);
    }

    #[Test]
    public function it_has_updates_relationship()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
        ]);

        $this->assertInstanceOf(ActivityUpdate::class, $activity->updates->first());
        $this->assertEquals($update->id, $activity->updates->first()->id);
    }

    #[Test]
    public function it_casts_attributes_correctly()
    {
        $activity = Activity::factory()->create([
            'activity_date' => '2024-01-15',
            'estimated_duration' => '02:30:00',
            'category_id' => $this->category->category_id,
        ]);

        $this->assertInstanceOf(Carbon::class, $activity->activity_date);
        $this->assertInstanceOf(Carbon::class, $activity->estimated_duration);
    }

    #[Test]
    public function it_can_filter_by_status()
    {
        Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'status' => 'done',
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
        ]);

        $pendingActivities = Activity::byStatus('pending')->get();

        $this->assertEquals(2, $pendingActivities->count());
        $this->assertTrue($pendingActivities->every(fn($activity) => $activity->status === 'pending'));
    }

    #[Test]
    public function it_can_filter_by_date_range()
    {
        Activity::factory()->create([
            'activity_date' => now()->subDays(10),
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'activity_date' => now()->subDays(5),
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'activity_date' => now()->subDays(2),
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'activity_date' => now()->addDays(5),
            'category_id' => $this->category->category_id,
        ]);

        $filteredActivities = Activity::byDateRange(now()->subDays(7), now()->subDays(1))->get();

        $this->assertEquals(2, $filteredActivities->count());
    }

    #[Test]
    public function it_can_filter_by_category()
    {
        $category2 = Category::factory()->create();

        Activity::factory()->create(['category_id' => $this->category->category_id]);
        Activity::factory()->create(['category_id' => $category2->category_id]);
        Activity::factory()->create(['category_id' => $this->category->category_id]);

        $filteredActivities = Activity::byCategory($this->category->category_id)->get();

        $this->assertEquals(2, $filteredActivities->count());
        $this->assertTrue($filteredActivities->every(fn($activity) => $activity->category_id === $this->category->category_id));
    }

    #[Test]
    public function it_can_filter_by_assignee()
    {
        Activity::factory()->create([
            'assigned_to' => $this->user1->id,
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'assigned_to' => $this->user2->id,
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'assigned_to' => $this->user1->id,
            'category_id' => $this->category->category_id,
        ]);

        $filteredActivities = Activity::byAssignee($this->user1->id)->get();

        $this->assertEquals(2, $filteredActivities->count());
        $this->assertTrue($filteredActivities->every(fn($activity) => $activity->assigned_to === $this->user1->id));
    }

    #[Test]
    public function it_returns_correct_status_color()
    {
        $pendingActivity = Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
        ]);
        $doneActivity = Activity::factory()->create([
            'status' => 'done',
            'category_id' => $this->category->category_id,
        ]);
        $cancelledActivity = Activity::factory()->create([
            'status' => 'cancelled',
            'category_id' => $this->category->category_id,
        ]);

        $this->assertEquals('bg-yellow-100 text-yellow-800', $pendingActivity->getStatusColor());
        $this->assertEquals('bg-green-100 text-green-800', $doneActivity->getStatusColor());
        $this->assertEquals('bg-red-100 text-red-800', $cancelledActivity->getStatusColor());
    }

    #[Test]
    public function it_returns_correct_priority_color()
    {
        $lowActivity = Activity::factory()->create([
            'priority' => 'low',
            'category_id' => $this->category->category_id,
        ]);
        $highActivity = Activity::factory()->create([
            'priority' => 'high',
            'category_id' => $this->category->category_id,
        ]);
        $criticalActivity = Activity::factory()->create([
            'priority' => 'critical',
            'category_id' => $this->category->category_id,
        ]);

        $this->assertEquals('bg-gray-100 text-gray-800', $lowActivity->getPriorityColor());
        $this->assertEquals('bg-orange-100 text-orange-800', $highActivity->getPriorityColor());
        $this->assertEquals('bg-red-100 text-red-800', $criticalActivity->getPriorityColor());
    }

    #[Test]
    public function it_can_be_mass_assigned()
    {
        $activityData = [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'priority' => 'medium',
            'status' => 'pending',
            'activity_date' => '2024-01-15',
            'category_id' => $this->category->category_id,
            'assigned_to' => $this->user1->id,
            'created_by' => $this->user2->id,
        ];

        $activity = Activity::create($activityData);

        $this->assertEquals('Test Activity', $activity->title);
        $this->assertEquals('Test Description', $activity->description);
        $this->assertEquals('medium', $activity->priority);
        $this->assertEquals('pending', $activity->status);
    }

    #[Test]
    public function it_orders_updates_by_update_time_desc()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        
        $update1 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'update_time' => now()->subDays(2),
            'updated_by' => $this->user1->id,
        ]);
        
        $update2 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'update_time' => now()->subDays(1),
            'updated_by' => $this->user1->id,
        ]);
        
        $update3 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'update_time' => now(),
            'updated_by' => $this->user1->id,
        ]);

        $updates = $activity->updates;

        $this->assertEquals($update3->id, $updates->first()->id);
        $this->assertEquals($update2->id, $updates->get(1)->id);
        $this->assertEquals($update1->id, $updates->last()->id);
    }
} 