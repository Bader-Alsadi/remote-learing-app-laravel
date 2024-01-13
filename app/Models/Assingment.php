<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assingment extends Model
{
    use HasFactory;

    protected $fillable =
    [
        "title",
        "description",
        "grade",
        "enrollment_id",
        "deadline",

    ];

    protected $casts = ["grade" => "integer", "enrollment_id" => "integer"];

    /**
     * Get all of the comments for the Assingment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the user that owns the Assingment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    // Enrollment::with("assingments.subject")
    // App\Models\Assignment::with('subject');
    // App\Models\Assingment::all();
    // pp\Models\Assignment::all();
}
