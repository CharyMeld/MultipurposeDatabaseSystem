<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates'; // or your actual table name
    protected $fillable = [
        'registration_number',
        'surname',
        'other_name',
        'maiden_name',
        'entry_mode',
        'country',
        'dob',
        'gender',
        'nationality',
        'fellowship_type',
        'faculty_id',
        'sub_speciality',
        'nysc_discharge_or_exemption',
        'accredited_training_program',
    ];
    
        // Medical schools
        public function medicalSchools()
        {
            return $this->hasMany(MedicalSchool::class);
        }

        // Institutions
        public function institutions()
        {
            return $this->hasMany(Institution::class);
        }

        // Postgraduate trainings
        public function postgraduateTrainings()
        {
            return $this->hasMany(PostgraduateTraining::class);
        }
        
        public function applications()
        {
            return $this->hasMany(Application::class);
        }
        
         public function results()
        {
            return $this->hasMany(Result::class, 'candidate_id');
        }

        // One candidate has many documents
        public function documents()
        {
            return $this->hasMany(Document::class, 'candidate_id');
        }


        // Faculty
        public function faculty()
        {
            return $this->belongsTo(Faculty::class);
        }

}

