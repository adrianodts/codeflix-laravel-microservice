<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $video;

    protected function setUp(): void {
        parent::setUp();
        $this->video = factory(Video::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.index', ['video' => $this->video->id]));
        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testInvalidationData()
    {
        $data = [
            'title' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = [
            'title' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);        
    }

    /*
    public function testStore()
    {
        $data = [
            'title' =>  'test',
            'description' =>  'test description',
            'year_launched' => 1998, 
            'opened' => 1, 
            'rating' => Video::RATING_LIST[0], 
            'duration' => 120
        ];
        $response = $this->assertStore($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure(['deleted_at', 'updated_at']);
        
        $data = [
            'title' => 'test',
            'description' =>  'test description',
        ];
        $this->assertStore($data, $data + ['title' => 'test', 'description' =>  'test description']);
        
    }

    public function testUpdate()
    {
        $data = [
            'title' => 'test',
            // 'description' => 'description',
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);

        // $data['description'] = null;
        // $this->assertUpdate($data, array_merge($data + ['description' => null]));
        
        // $data['description'] = 'test';
        // $this->assertUpdate($data, array_merge($data + ['description' => 'test']));
    }
    */
    public function testDestroy()
    {
        $response = $this->json('DELETE', route('videos.destroy', ['video' => $this->video->id]), []);
        $response->assertStatus(204);
        $this->assertNull(Video::find($this->video->id));
        $this->assertNotNull(Video::withTrashed()->find($this->video->id));
    }

    protected function routeStore() {
        return route('videos.store');
    }

    protected function routeUpdate() {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model() {
        return Video::class;
    }
}
