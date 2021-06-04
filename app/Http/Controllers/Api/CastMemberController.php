<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;
use App\Models\Enums\CastMemberType;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CastMemberController extends BasicCrudController
{
    private $rules;
    public function __construct()
    {
        $this-> rules = [
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', [CastMemberType::ACTOR, CastMemberType::DIRECTOR]),
            //'type' => 'required|integer|min:1|max:2',
            'is_active' => 'boolean'
        ];  
    } 

    protected function model() {
        return CastMember::class;
    }

    protected function rulesStore() {
        return $this->rules;
    } 

    public function rulesUpdate()
    {
        return $this->rules;
    }
}
