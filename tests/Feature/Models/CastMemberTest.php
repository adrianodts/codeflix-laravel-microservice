<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use App\Models\Enums\CastMemberType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\Rules\DatabaseRule;
use Prophecy\Call\Call;
use Tests\TestCase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(CastMember::class, 1)->create();
        $castMembers = CastMember::all();
        // dd($castMembers);
        $this->assertCount(1, $castMembers);
        $castMemberKey = array_keys($castMembers->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'type',
            'is_active',
            'deleted_at',
            'created_at',
            'updated_at'
        ],$castMemberKey);
    }

    public function testCreate()
    {
        $castMember = CastMember::create([
            'name' => 'test1',
            'type' => CastMemberType::ACTOR
        ]);
        // dd($castMember);
        $castMember->refresh();
        $this->assertEquals(36, strlen($castMember->id));
        $this->assertEquals('test1', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertTrue($castMember->is_active);

        $castMember = CastMember::create([
            'name' => 'test1',
            'type' => CastMemberType::ACTOR
        ]);
        $this->assertNotNull($castMember->name);
        $this->assertNotNull($castMember->type);

        $castMember = CastMember::create([
            'name' => 'test1',
            'type' => CastMemberType::ACTOR,
            'is_active' => false
        ]);
        $this->assertFalse($castMember->is_active);
        
        $castMember = CastMember::create([
            'name' => 'test1',
            'type' => CastMemberType::ACTOR,
            'is_active' => true
        ]);
        $this->assertTrue($castMember->is_active);
        
    }

    public function testUpdate()
    {
        $castMember = factory(CastMember::class)->create([
            'is_active' => false
        ]);
        $data = [
            'name' => 'test_name_updated',
            'type' => CastMemberType::DIRECTOR,
            'is_active_true' => false
        ];
        $castMember->update($data);
        foreach($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }
    }

    public function testDelete()
    {
        $castMember = factory(CastMember::class)->create([
            'is_active' => false
        ]);
        $castMember->delete();
        $this->assertNull(CastMember::find($castMember->id));

        $castMember->restore();
        $this->assertNotNull(CastMember::find($castMember->id));
    }
}