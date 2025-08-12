<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles'; // Optional, only if your table is not "roles"

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_at',
    ];

    public $timestamps = false; // Since you're using 'time_created' instead of default Laravel timestamps
}

