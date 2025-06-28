<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ActivityUpdate;
use App\Models\Activity;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class ActivityUpdateTest extends TestCase
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
    public function it_can_create_an_activity_update()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'previous_status' => 'pending',
            'new_status' => 'in_progress',
            'remark' => 'Started working on the task',
        ]);

        $this->assertDatabaseHas('activity_updates', [
            'previous_status' => 'pending',
            'new_status' => 'in_progress',
            'remark' => 'Started working on the task',
        ]);
    }

    #[Test]
    public function it_has_activity_relationship()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
        ]);

        $this->assertInstanceOf(Activity::class, $update->activity);
        $this->assertEquals($activity->id, $update->activity->id);
    }

    #[Test]
    public function it_has_updater_relationship()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
        ]);

        $this->assertInstanceOf(User::class, $update->updater);
        $this->assertEquals($this->user1->id, $update->updater->id);
    }

    #[Test]
    public function it_casts_attributes_correctly()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'update_time' => '2024-01-15 10:30:00',
            'user_bio_details' => ['name' => 'John Doe', 'position' => 'Developer'],
        ]);

        $this->assertInstanceOf(Carbon::class, $update->update_time);
        $this->assertIsArray($update->user_bio_details);
        $this->assertEquals('John Doe', $update->user_bio_details['name']);
    }

    #[Test]
    public function it_can_be_mass_assigned()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);

        $updateData = [
            'previous_status' => 'pending',
            'new_status' => 'done',
            'remark' => 'Task completed successfully',
            'update_time' => now(),
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
        ];

        $update = ActivityUpdate::create($updateData);

        $this->assertEquals('pending', $update->previous_status);
        $this->assertEquals('done', $update->new_status);
        $this->assertEquals('Task completed successfully', $update->remark);
    }

    #[Test]
    public function it_can_have_null_previous_status()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'previous_status' => null,
            'new_status' => 'pending',
        ]);

        $this->assertNull($update->previous_status);
        $this->assertEquals('pending', $update->new_status);
    }

    #[Test]
    public function it_can_have_null_remark()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);
        
        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'remark' => null,
        ]);

        $this->assertNull($update->remark);
    }

    #[Test]
    public function it_stores_user_bio_details_correctly()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);

        $update = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'user_bio_details' => $this->user1->getBioDetails(),
        ]);

        $this->assertEquals($this->user1->name, $update->user_bio_details['name']);
        $this->assertEquals($this->user1->position, $update->user_bio_details['position']);
        $this->assertEquals($this->user1->email, $update->user_bio_details['email']);
    }

    #[Test]
    public function it_can_track_status_transitions()
    {
        $activity = Activity::factory()->create([
            'status' => 'pending',
            'category_id' => $this->category->category_id,
        ]);

        // First update: pending -> in_progress
        $update1 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'previous_status' => 'pending',
            'new_status' => 'in_progress',
            'remark' => 'Started working',
        ]);

        // Second update: in_progress -> done
        $update2 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'previous_status' => 'in_progress',
            'new_status' => 'done',
            'remark' => 'Completed the task',
        ]);

        $this->assertEquals('pending', $update1->previous_status);
        $this->assertEquals('in_progress', $update1->new_status);
        $this->assertEquals('in_progress', $update2->previous_status);
        $this->assertEquals('done', $update2->new_status);
    }

    #[Test]
    public function it_can_have_multiple_updates_for_same_activity()
    {
        $activity = Activity::factory()->create([
            'category_id' => $this->category->category_id,
        ]);

        $update1 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'update_time' => now()->subHours(2),
        ]);

        $update2 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'update_time' => now()->subHour(),
        ]);

        $update3 = ActivityUpdate::factory()->create([
            'activity_id' => $activity->id,
            'updated_by' => $this->user1->id,
            'update_time' => now(),
        ]);

        $this->assertEquals(3, $activity->updates->count());
    }
} 