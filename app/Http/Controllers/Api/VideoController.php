<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Exception;
use Illuminate\Auth\Events\Validated;

class VideoController extends BasicCrudController
{
    private $rules;

    public function __construct()
    {
        $this-> rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' =>  'required|in:' . implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
            'genres_id' => 'required|array|exists:genres,id,deleted_at,NULL'
        ];  
    } 

    public function store(Request $request) {
        // dd($request);
        // try {
        //     DB::beginTransaction();
        //     throw new Exception("Erro durante o cadastro do video");
        //     DB::commit();
        // } catch (Exception $e) {
        //     DB::rollback();
        // }
        $self = $this;
        $validatedData = $this->validate($request, $this->rulesStore());
        $obj = DB::transaction(function () use ($request, $validatedData, $self) {
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($obj, $request); 
            //throw new Exception("Erro durante a atualização do video");
            return $obj;
        });
        $obj->refresh();
        return $obj;
    }

    public function update(Request $request, $id) {
        // dd($request);
        $self = $this;
        $obj = $this->findOrFail($id);
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $obj = DB::transaction(function () use ($request, $validatedData, $obj, $self) {
            $obj->update($validatedData);
            $self->handleRelations($obj, $request);
            //throw new Exception("Erro durante a atualização do video");
            return $obj;
        });
        $obj->refresh();
        return $obj;
    }

    protected function handleRelations($video, Request $request) {
        $video->categories()->sync($request->get('categories_id'));
        $video->genres()->sync($request->get('genres_id'));
    }

    protected function model() {
        return Video::class;
    }

    protected function rulesStore() {
        return $this->rules;
    } 

    public function rulesUpdate()
    {
        return $this->rules;
    }
}
