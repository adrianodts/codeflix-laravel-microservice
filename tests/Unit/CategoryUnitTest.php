<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function textIfUseTraits() 
    {
        Genre::create(['name' => 'test']);
        $traits = [
            SoftDeletes::class,
            isUuid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active']; 
        $category = new Category();
        $this->assertEquals($fillable, $category->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = ['is_active' => 'boolean']; 
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at']; 
        $category = new Category();
        //dd($dates, $category->getDates());
        foreach($dates as $date) {
            $this->assertContains($date, $category->getDates());
        }
        $this->assertCount(count($dates), $category->getDates());
    }

    public function testIncrementingAttribute()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }
}
