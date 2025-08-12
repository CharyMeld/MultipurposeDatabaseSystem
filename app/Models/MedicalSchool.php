<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSchool extends Model
{
    protected $table = 'medical_school';

    protected $fillable = [
        'candidate_id',
        'name',
        'start_date',
        'end_date',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

