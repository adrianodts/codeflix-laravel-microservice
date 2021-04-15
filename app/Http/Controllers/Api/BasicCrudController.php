<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Tests\Stubs\Models\CategoryStub;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();

    protected function index()
    {
        return $this->model()::all();
    }

}
