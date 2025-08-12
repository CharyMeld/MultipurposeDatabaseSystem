<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents'; // plural form, adjust if your table is named differently

    protected $fillable = [
        'candidate_id',
        'application_id',
        'title',
        'description',
        'location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relationship to Candidate model
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Relationship to Application model
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Override delete to remove the file from storage when deleting the model
     */
    public function delete()
    {
        // Delete the file from storage if exists
        if ($this->location && file_exists(public_path($this->location))) {
            @unlink(public_path($this->location));
        }

        return parent::delete();
    }
}

