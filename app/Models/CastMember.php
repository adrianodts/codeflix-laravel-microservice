<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use SoftDeletes, Traits\Uuid;
    protected $table = 'castmembers';
    protected $fillable = ['name', 'type', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $casts = [
        'type' => 'required|integer|min:1|max:2',
        'is_active' => 'boolean'
    ];
    public $incrementing = false;
}
