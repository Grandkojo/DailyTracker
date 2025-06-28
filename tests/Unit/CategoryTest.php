<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_category()
    {
        $category = Category::factory()->create([
            'name' => 'Development',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Development',
        ]);
    }

    #[Test]
    public function it_automatically_generates_category_id_on_creation()
    {
        $category = Category::factory()->create([
            'category_id' => null,
        ]);

        $this->assertNotNull($category->category_id);
        $this->assertTrue(Str::isUuid($category->category_id));
    }


    #[Test]
    public function it_can_be_mass_assigned()
    {
        $categoryData = [
            'name' => 'Testing',
        ];

        $category = Category::create($categoryData);

        $this->assertEquals('Testing', $category->name);
    }

    #[Test]
    public function it_uses_uuid_as_primary_key()
    {
        $category = Category::factory()->create();

        $this->assertTrue(Str::isUuid($category->category_id));
        $this->assertNotEquals($category->id, $category->category_id);
    }

    #[Test]
    public function it_can_be_retrieved_by_category_id()
    {
        $category = Category::factory()->create();
        
        $retrievedCategory = Category::where('category_id', $category->category_id)->first();

        $this->assertEquals($category->id, $retrievedCategory->id);
        $this->assertEquals($category->name, $retrievedCategory->name);
    }

    #[Test]
    public function it_can_be_updated()
    {
        $category = Category::factory()->create([
            'name' => 'Old Name',
        ]);

        $category->update([
            'name' => 'New Name',
        ]);

        $this->assertEquals('New Name', $category->fresh()->name);
    }

    #[Test]
    public function it_can_be_deleted()
    {
        $category = Category::factory()->create();

        $categoryId = $category->category_id;
        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'category_id' => $categoryId,
        ]);
    }

    #[Test]
    public function it_can_be_listed_with_activities_count()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Activity::factory()->count(2)->create(['category_id' => $category1->category_id]);
        Activity::factory()->count(1)->create(['category_id' => $category2->category_id]);

        $categoriesWithCount = Category::withCount('activities')->get();

        $category1WithCount = $categoriesWithCount->where('category_id', $category1->category_id)->first();
        $category2WithCount = $categoriesWithCount->where('category_id', $category2->category_id)->first();

        $this->assertEquals(2, $category1WithCount->activities_count);
        $this->assertEquals(1, $category2WithCount->activities_count);
    }
} 