<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;

class CategoryController extends BasicCrudController
{
    private $rules = [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'is_active' => 'boolean'
    ];

    protected function model() {
        return Category::class;
    }

    protected function rulesStore() {
        return $this->rules;
    } 

    public function rulesUpdate()
    {
        return $this->rules;
    }
}
