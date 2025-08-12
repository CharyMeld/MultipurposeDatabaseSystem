<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomUser extends Model
{
    protected $table = 'User'; // Table name as in your database
    protected $primaryKey = 'id'; // Primary key column

    public $timestamps = false; // Because your table does NOT have created_at / updated_at

    // Optional: Specify fillable fields
    protected $fillable = [
        'login_name',
        'login_password',
        'firstname',
        'lastname',
        'middlename',
        'role_id',
        'gender',
        'marital_status',
        'time_created',
        'status',
    ];
}

