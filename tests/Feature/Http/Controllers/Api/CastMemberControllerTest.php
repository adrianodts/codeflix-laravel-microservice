<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $castMember;

    protected function setUp(): void {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('castMembers.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('castMembers.index', ['castMember' => $this->castMember->id]));
        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);
        
        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testStore()
    {
        $data = [
            'name' =>  'test'
        ];
        $response = $this->assertStore($data, $data + ['is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure(['deleted_at', 'updated_at']);

        $data = [
            'name' => 'test',
            'is_active' => false
        ];
        $this->assertStore($data, $data + ['name' => 'test', 'is_active' => false]);
    }

    public function testUpdate()
    {
        $this->castMember = factory(CastMember::class)->create([
            'is_active' => false
        ]);
        $data = [
            'name' => 'test',
            'is_active' => true
        ];
        $response = $this->assertUpdate($data, $data + ['is_active' => true, 'deleted_at' => null]);

        $data['name'] = 'test';
        $this->assertUpdate($data, array_merge($data + ['name' => 'test']));
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('castMembers.destroy', ['castMember' => $this->castMember->id]), []);
        $response->assertStatus(204);
        $this->assertNull(CastMember::find($this->castMember->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->castMember->id));
    }

    protected function routeStore() {
        return route('castMembers.store');
    }

    protected function routeUpdate() {
        return route('castMembers.update', ['castMember' => $this->castMember->id]);
    }

    protected function model() {
        return CastMember::class;
    }
}
