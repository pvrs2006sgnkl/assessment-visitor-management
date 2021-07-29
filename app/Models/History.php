<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_id', 
        'user_id', 
        'meet_person_name', 
        'entered_at', 
        'exited_at',
        'expired_at'
    ];

    public function unit() {
        return $this->belongsToMany(
            History::class,
            'unit_users',
            'user_id',
            'unit_id',
        )
        ->withTimestamps()
        ->as('user_unit');
    }

    public function units() {
        return $this->hasManyThrough(
            History::class,
            Unituser::class,
            'user_id',
            'unit_id',
            'id',
            'unit_id'
        );
    }

    public function user() {
        return $this->belongsToMany(
            User::class,
            'unit_users',
            'user_id',
            'id',
            'id',
        )
        ->withTimestamps()
        ->as('user');
    }

    public function users() {
        return $this->hasManyThrough(
            User::class,
            Unituser::class,
            'user_id',
            'id',
            'id',
            'id'
        );
    }
}
