<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentDetile extends Model
{
    use HasFactory;

    protected $fillable =
    [
        "department_id",
        "semester",
        "level",
        "start_date",
        "end_date"
    ];

    protected $casts = [
        "level" => "string"
    ];



    /**
     * Get the department that owns the DepartmentDetile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all of the comments for the DepartmentDetile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function students()
    {
        return $this->hasMany(Student::class, 'department_detile_id');
    }

     /**
     * Get all of the comments for the DepartmentDetile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects()
    {
        return $this->hasMany(Enrollment::class, 'department_detile_id');
    }
}
