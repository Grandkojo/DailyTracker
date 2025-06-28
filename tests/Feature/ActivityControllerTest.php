<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Activity;
use App\Models\User;
use App\Models\Category;
use App\Models\ActivityUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'support_team']);
        $this->category = Category::factory()->create();
    }

    #[Test]
    public function it_shows_activities_index_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/activities');

        $response->assertStatus(200);
        $response->assertViewIs('activities.index');
        $response->assertViewHas('activities');
        $response->assertViewHas('teamMembers');
        $response->assertViewHas('categories');
    }

    #[Test]
    public function it_shows_create_activity_page()
    {
        $this->actingAs($this->user);

        $response = $this->get('/activities/create');

        $response->assertStatus(200);
        $response->assertViewIs('activities.create');
        $response->assertViewHas('teamMembers');
    }

    #[Test]
    public function it_can_create_an_activity()
    {
        $this->actingAs($this->user);

        $activityData = [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'category' => $this->category->category_id,
            'priority' => 'high',
            'activity_date' => '2024-01-15',
            'estimated_duration' => '02:30',
            'assigned_to' => $this->user->id,
        ];

        $response = $this->post('/activities', $activityData);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'category_id' => $this->category->category_id,
            'priority' => 'high',
            'status' => 'pending',
            'created_by' => $this->user->id,
        ]);

        // Check that initial activity update was created
        $activity = Activity::where('title', 'Test Activity')->first();
        $this->assertDatabaseHas('activity_updates', [
            'activity_id' => $activity->id,
            'updated_by' => $this->user->id,
            'previous_status' => null,
            'new_status' => 'pending',
            'remark' => 'Activity created',
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_activity()
    {
        $this->actingAs($this->user);

        $response = $this->post('/activities', []);

        $response->assertSessionHasErrors(['title', 'description', 'category', 'priority', 'activity_date']);
    }

    #[Test]
    public function it_validates_category_exists_when_creating_activity()
    {
        $this->actingAs($this->user);

        $response = $this->post('/activities', [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'category' => 'non-existent-uuid',
            'priority' => 'high',
            'activity_date' => '2024-01-15',
        ]);

        $response->assertSessionHasErrors(['category']);
    }

    #[Test]
    public function it_shows_activity_details()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->get("/activities/{$activity->id}");

        $response->assertStatus(200);
        $response->assertViewIs('activities.show');
        $response->assertViewHas('activity', $activity);
        $response->assertViewHas('teamMembers');
    }

    #[Test]
    public function it_shows_edit_activity_page()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->get("/activities/{$activity->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('activities.edit');
        $response->assertViewHas('activity', $activity);
        $response->assertViewHas('teamMembers');
    }

    #[Test]
    public function it_can_update_an_activity()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $updateData = [
            'title' => 'Updated Activity',
            'description' => 'Updated Description',
            'category' => $this->category->category_id,
            'priority' => 'medium',
            'activity_date' => '2024-01-20',
            'estimated_duration' => '01:30',
            'assigned_to' => $this->user->id,
        ];

        $response = $this->put("/activities/{$activity->id}", $updateData);

        $response->assertRedirect("/activities/{$activity->id}");
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'title' => 'Updated Activity',
            'description' => 'Updated Description',
            'priority' => 'medium',
        ]);
    }

    #[Test]
    public function it_can_delete_an_activity()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->delete("/activities/{$activity->id}");

        $response->assertRedirect('/activities');
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    #[Test]
    public function it_can_update_activity_status()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'status' => 'pending',
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->post("/activities/{$activity->id}/status", [
            'status' => 'in_progress',
            'remark' => 'Started working on the task',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => 'in_progress',
        ]);

        // Check that activity update was created
        $this->assertDatabaseHas('activity_updates', [
            'activity_id' => $activity->id,
            'updated_by' => $this->user->id,
            'previous_status' => 'pending',
            'new_status' => 'in_progress',
            'remark' => 'Started working on the task',
        ]);
    }

    #[Test]
    public function it_validates_status_update_data()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->post("/activities/{$activity->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['status']);
    }

    #[Test]
    public function it_can_get_activity_updates()
    {
        $this->actingAs($this->user);
        
        $activity = Activity::factory()->create([
            'created_by' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        ActivityUpdate::factory()->count(3)->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user->id,
        ]);

        $response = $this->get("/activities/{$activity->id}/updates");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    #[Test]
    public function it_filters_activities_by_status()
    {
        $this->actingAs($this->user);

        Activity::factory()->create(['status' => 'pending', 'category_id' => $this->category->category_id]);
        Activity::factory()->create(['status' => 'done', 'category_id' => $this->category->category_id]);
        Activity::factory()->create(['status' => 'pending', 'category_id' => $this->category->category_id]);

        $response = $this->get('/activities?status=pending');

        $response->assertStatus(200);
        $response->assertViewHas('activities');
        
        $activities = $response->viewData('activities');
        $this->assertEquals(2, $activities->count());
    }

    #[Test]
    public function it_filters_activities_by_category()
    {
        $this->actingAs($this->user);

        $category2 = Category::factory()->create();

        Activity::factory()->create(['category_id' => $this->category->category_id]);
        Activity::factory()->create(['category_id' => $category2->category_id]);
        Activity::factory()->create(['category_id' => $this->category->category_id]);

        $response = $this->get("/activities?category={$this->category->category_id}");

        $response->assertStatus(200);
        $response->assertViewHas('activities');
        
        $activities = $response->viewData('activities');
        $this->assertEquals(2, $activities->count());
    }

    #[Test]
    public function it_filters_activities_by_date_range()
    {
        $this->actingAs($this->user);

        Activity::factory()->create([
            'activity_date' => '2024-01-10',
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'activity_date' => '2024-01-15',
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'activity_date' => '2024-01-20',
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->get('/activities?start_date=2024-01-12&end_date=2024-01-18');

        $response->assertStatus(200);
        $response->assertViewHas('activities');
        
        $activities = $response->viewData('activities');
        $this->assertEquals(1, $activities->count());
    }

    #[Test]
    public function it_filters_activities_by_assignee()
    {
        $this->actingAs($this->user);

        $user2 = User::factory()->create();

        Activity::factory()->create([
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'assigned_to' => $user2->id,
            'category_id' => $this->category->category_id,
        ]);
        Activity::factory()->create([
            'assigned_to' => $this->user->id,
            'category_id' => $this->category->category_id,
        ]);

        $response = $this->get("/activities?assigned_to={$this->user->id}");

        $response->assertStatus(200);
        $response->assertViewHas('activities');
        
        $activities = $response->viewData('activities');
        $this->assertEquals(2, $activities->count());
    }

    #[Test]
    public function it_requires_authentication_for_activities()
    {
        $response = $this->get('/activities');
        $response->assertRedirect('/login');

        $response = $this->get('/activities/create');
        $response->assertRedirect('/login');

        $activity = Activity::factory()->create(['category_id' => $this->category->category_id]);
        
        $response = $this->get("/activities/{$activity->id}");
        $response->assertRedirect('/login');
    }
} 