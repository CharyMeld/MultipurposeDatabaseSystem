<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $table = 'institution';

    protected $fillable = [
        'candidate_id',
        'name',
        'degree',
        'start_date',
        'end_date',
        'institution_type',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

