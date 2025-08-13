<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostgraduateTraining extends Model
{
    protected $table = 'postgraduatetraining';

       protected $fillable = [
        'candidate_id',
        'name',      // store the post_training_school here
        'degree',
        'start_date',
        'end_date',
    ];

    
    
    public function postgraduates()
    {
        return $this->hasMany(PostgraduateTraining::class);
    }


    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

