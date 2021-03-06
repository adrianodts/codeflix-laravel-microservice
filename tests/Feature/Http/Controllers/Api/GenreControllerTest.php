<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use App\Models\Category;
use App\Http\Controllers\Api\GenreController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\Exceptions\TestException;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;
use Mockery;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $genre;

    protected function setUp(): void {
        parent::setUp();
        $this->genre = factory(Genre::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('genres.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.index', ['genre' => $this->genre->id]));
        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => '',
            'categories_id' => ''
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

        $data = [
            'categories_id' => [100]
        ];
        $this->assertInvalidationInStoreAction($data, 'exists');
        $this->assertInvalidationInUpdateAction($data, 'exists');

        $category = factory(Category::class)->create();
        $category->delete();
        $data = [
            'categories_id' => [$category->id]
        ];
        $this->assertInvalidationInStoreAction($data, 'exists');
        $this->assertInvalidationInUpdateAction($data, 'exists');
    }

    public function testStore()
    {
        $category = factory(Category::class)->create();
        $data = [
            'name' =>  'test'
        ];
        $response = $this->assertStore(
            $data +  ['categories_id' => [$category->id]], 
            $data + ['is_active' => true, 'deleted_at' => null]
        );
        
        $response->assertJsonStructure(['deleted_at', 'updated_at']);
        $this->assertHasCategory($response->json('id'), $category->id);
        
        $data = [
            'name' => 'test',
            'is_active' => false
        ];
        $this->assertStore(
            $data + ['categories_id' => [$category->id]],
            $data + ['name' => 'test', 'is_active' => false]);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create();
        $data = [
            'name' =>  'test',
            'is_active' => true
        ];
        $response = $this->assertUpdate(
            $data +  ['categories_id' => [$category->id]],
            $data + ['is_active' => true, 'deleted_at' => null]
        );

        $this->assertHasCategory($response->json('id'), $category->id);
    }

    public function assertHasCategory($genreId, $categoryId) {
        $this->assertDatabaseHas('category_genre', [
            'genre_id' => $genreId,
            'category_id' => $categoryId
        ]);
    }

    public function testRollbackStore()
    {
        $controller = Mockery::mock(GenreController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller
            ->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn(['name' => 'test']);

        $controller
            ->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());
        
        $request = Mockery::mock(Request::class);
        $hasError = false;
        try{
            $controller->store($request);
        }catch (TestException $e){
            $this->assertCount(1, Genre::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testRollbackUpdate()
    {
        $controller = Mockery::mock(GenreController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller
            ->shouldReceive('findOrFail')
            ->withAnyArgs()
            ->andReturn($this->genre);

        $controller
            ->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn(['name' => 'test']);

        $controller
            ->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());
        
        $request = Mockery::mock(Request::class);
        $hasError = false;
        try{
            $controller->update($request, 1);
        }catch (TestException $e){
            $this->assertCount(1, Genre::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $this->genre->id]), []);
        $response->assertStatus(204);
        $this->assertNull(Genre::find($this->genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($this->genre->id));
    }

    protected function routeStore() {
        return route('genres.store');
    }

    protected function routeUpdate() {
        return route('genres.update', ['genre' => $this->genre->id]);
    }

    protected function model() {
        return Genre::class;
    }
}
