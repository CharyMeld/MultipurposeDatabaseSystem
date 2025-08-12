<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // Define the table if not default plural of model name
    protected $table = 'applications'; // Use plural lowercase snake_case

    // If you have a composite primary key, Laravel doesn't support it out of the box.
    // But since your composite key is ('application_type', 'exam_number'), you may want to add a surrogate 'id' column.
    protected $primaryKey = 'id'; // Assuming you add an auto-increment id

    // Mass assignable fields
    protected $fillable = [
        'candidate_id',
        'code',
        'application_type',
        'exam_number',
        'previous_attempts',
        'exam_date',
        'other_details',
        'status',
        'application_date',
        'approved_date',
        'passed',
        'exam_center_id',
    ];

    // Casts for date/time and booleans
    protected $casts = [
        'exam_date' => 'date',
        'application_date' => 'date',
        'approved_date' => 'date',
        'status' => 'boolean',
        'passed' => 'boolean',
    ];

    // Relationships

    // Candidate owning this application (many applications belong to one candidate)
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Assuming you have Assessment, Document, Result models, these are hasMany relationships
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}

