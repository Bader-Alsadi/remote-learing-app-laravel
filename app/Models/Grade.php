<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model

{
    use HasFactory; 
    protected $fillable = ["student_id", "enrollment_id", "final_mark"];
    
    /**
     * Get all of the Grades for the Grade
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Grades()
    {
        return $this->hasMany(Grade::class);
    }

}
