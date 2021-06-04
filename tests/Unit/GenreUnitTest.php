<?php

namespace Tests\Unit;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class GenreUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function textIfUseTraits() 
    {
        Genre::create(['name' => 'test']);
        $traits = [
            SoftDeletes::class,
            isUuid::class
        ];
        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'is_active']; 
        $genre = new Genre();
        $this->assertEquals($fillable, $genre->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = ['is_active' => 'boolean']; 
        $genre = new Genre();
        $this->assertEquals($casts, $genre->getCasts());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at']; 
        $genre = new Genre();
        foreach($dates as $date) {
            $this->assertContains($date, $genre->getDates());
        }
        $this->assertCount(count($dates), $genre->getDates());
    }

    public function testIncrementingAttribute()
    {
        $genre = new Genre();
        $this->assertFalse($genre->incrementing);
    }
}
