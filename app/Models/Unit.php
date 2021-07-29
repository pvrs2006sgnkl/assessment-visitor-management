<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Unituser;
use App\Models\History;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsToMany(
            User::class,
            'unit_users',
            'unit_id',
            'id',
            'id',
        )
        ->withTimestamps()
        ->withPivot('user_id');
    }

    public function users() {
        return $this->hasManyThrough(
            User::class,
            Userunit::class,
            'user_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function history() {
        return $this->belongsToMany(
            History::class,
            'unit_users',
            'user_id',
            'unit_id',
            'id',
        )
        ->withTimestamps();
    }

    public function histories() {
        return $this->hasManyThrough(
            History::class,
            Userunit::class,
            'user_id',
            'unit_id',
            'id',
            'id'
        )
        ->withTimestamps();
    }
}
