<?php

namespace Tests\Unit;

use App\Models\CastMember;
use App\Models\Enums\CastMemberType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function textIfUseTraits() 
    {
        CastMember::create(['name' => 'test', 'type' => CastMemberType::ACTOR]);
        $traits = [
            SoftDeletes::class,
            isUuid::class
        ];
        $castMemberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'type', 'is_active']; 
        $castMember = new CastMember();
        $this->assertEquals($fillable, $castMember->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = ['is_active' => 'boolean', 'type' => 'required|integer|min:1|max:2']; 
        $castMember = new CastMember();
        $this->assertEquals($casts, $castMember->getCasts());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at']; 
        $castMember = new CastMember();
        foreach($dates as $date) {
            $this->assertContains($date, $castMember->getDates());
        }
        $this->assertCount(count($dates), $castMember->getDates());
    }

    public function testIncrementingAttribute()
    {
        $castMember = new CastMember();
        $this->assertFalse($castMember->incrementing);
    }
}
