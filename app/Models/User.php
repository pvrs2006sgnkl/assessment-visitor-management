<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Unit;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'user_type', 
        'nric',
        'unit_id'
    ];

    public function unit() {
        return $this->belongsToMany(
            Unit::class,
            'unit_users',
            'user_id',
            'unit_id',
        )
        ->withTimestamps()
        ->as('user_unit');
    }

    public function units() {
        return $this->hasManyThrough(
            Unit::class,
            Unituser::class,
            'user_id',
            'id',
            'id',
            'unit_id',
        );
    }

    public function history() {
        return $this->hasMany(
            History::class,
            'user_id',
            'id',
        ); 
        // return $this->belongsToMany(
        //     History::class,
        //     'unit_users',
        //     'user_id',
        //     'unit_id',
        // )
        // ->withTimestamps()
        // ->as('user_history');
    }

    public function histories() {
        return $this->hasManyThrough(
            History::class,
            Unituser::class,
            'user_id',
            'user_id',
            'unit_id',
            'unit_id'
        )
        ->withPivot('id');
    }
}
