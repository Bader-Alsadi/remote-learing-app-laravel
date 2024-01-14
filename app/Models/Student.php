<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "department_detile_id",
        "mark"
    ];

    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(DepartmentDetile::class, "department_detile_id");
    }

    /**
     * Get all of the comments for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissins()
    {
        return $this->hasMany(Submission::class);
    }
}
