<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Unituser extends Pivot
{
    protected $table = 'unit_users';

    protected $guards = [];
}
