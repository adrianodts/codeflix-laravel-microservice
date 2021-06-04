<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Auth\Events\Validated;

class VideoController extends BasicCrudController
{
    private $rules;

    public function __construct()
    {
        $this-> rules = [
            'title' => 'required|max:255',
            'is_active' => 'boolean'
        ];  
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
