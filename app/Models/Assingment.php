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

    /**
     * Get all of the comments for the Assingment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
