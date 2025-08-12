<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
        'login_name',
        'email',
        'password',
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'marital_status',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays/serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Optional: define relationship if needed
    public function role()
    {
        return $this->belongsTo(Role::class); // Make sure Role model exists
    }
}

