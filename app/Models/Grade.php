<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model

{
    use HasFactory;
    protected $fillable = ["student_id", "enrollment_id", "final_mark"];
    protected $caste = ["final_mark" => "double"];


    /**
     * Get the subject that owns the Grade
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Enrollment::class, "enrollment_id");
    }

    /**
     * Get the student that owns the Grade
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
