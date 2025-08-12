<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';

    // The fields that are mass assignable
    protected $fillable = [
        'application_id',
        'candidate_id',
        'month',
        'year',
        'score',
        'grade',
        'status',
    ];

    // Cast the status to boolean and score to float for convenience
    protected $casts = [
        'status' => 'boolean',
        'score' => 'float',
    ];

    /**
     * Relationship to Application model
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Relationship to Candidate model
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}

