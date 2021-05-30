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
        'type' => 'required|integer|min:1|max:2',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return CastMember::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $castMember = CastMember::create($request->all());
        $castMember->refresh();
        return $castMember;
    }

    public function show(CastMember $castMember)
    {
        return $castMember;
    }

    public function update(Request $request, CastMember $castMember)
    {
        $this->validate($request, $this->rules);
        $castMember->update($request->all());
        return $castMember;
    }

    public function destroy(CastMember $castMember)
    {
        $castMember->delete();
        return response()->noContent();
    }
}
