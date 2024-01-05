<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable =
    [
        "student_id",
        "assingment_id",
        "submissions_date",
        "path",
        "state",
        "grade",
    ];

    protected $casts = ["state"=>"boolean"];

    /**
     * Get the student that owns the Submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the assingment that owns the Submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assingment()
    {
        return $this->belongsTo(Assingment::class,);
    }
}
