<?php

namespace Tests\Unit;

use App\Models\Video;
use App\Models\Genre;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class VideoUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function textIfUseTraits() 
    {
        $traits = [
            SoftDeletes::class,
            isUuid::class
        ];
        $videoTraits = array_keys(class_uses(Video::class));
        $this->assertEquals($traits, $videoTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = [
            'id',
            'title', 
            'description', 
            'year_launched', 
            'opened', 
            'rating', 
            'duration'
        ]; 
        $video = new Video();
        $this->assertEquals($fillable, $video->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = [ 
            'id' => 'string',
            'opened' => 'boolean', 
            'year_launched' => 'integer', 
            'duration' => 'integer'
        ]; 
        $video = new Video();
        $this->assertEquals($casts, $video->getCasts());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at']; 
        $video = new Video();
        //dd($dates, $video->getDates());
        foreach($dates as $date) {
            $this->assertContains($date, $video->getDates());
        }
        $this->assertCount(count($dates), $video->getDates());
    }

    public function testIncrementingAttribute()
    {
        $video = new Video();
        $this->assertFalse($video->incrementing);
    }
}
