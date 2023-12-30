<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "note",
        "lecturer_data",
        "enrollment_id"
    ];



    /**
     * Get all of the comments for the Lecturer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matirels()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get the enrollment that owns the Lecturer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
