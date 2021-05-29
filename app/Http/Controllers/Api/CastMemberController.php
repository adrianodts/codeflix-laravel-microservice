<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;
use App\Models\Enums\CastMemberType;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CastMemberController extends BasicCrudController
{
    protected function model() {
        return CastMember::class;
    }

    protected function rulesStore() {
        return $this->rules;
    } 

    private $rules = [
        'name' => 'required|max:255',
        'type' => 'required|integer|Rule::in(CastMemberType::$types)',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return CastMember::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $genre = CastMember::create($request->all());
        $genre->refresh();
        return $genre;
    }

    public function show(CastMember $genre)
    {
        return $genre;
    }

    public function update(Request $request, CastMember $genre)
    {
        $this->validate($request, $this->rules);
        $genre->update($request->all());
        return $genre;
    }

    public function destroy(CastMember $genre)
    {
        $genre->delete();
        return response()->noContent();
    }
}
