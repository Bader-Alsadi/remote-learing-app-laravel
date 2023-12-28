<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable =
    [
        "user_id",
        "subject_id",
        "department_detile_id",
        "year",
        "scientific_method",
    ];

    /**
     * Get the teacher that owns the Enrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(User::class,);
    }

      /**
     * Get the teacher that owns the Enrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class,);
    }

      /**
     * Get the teacher that owns the Enrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deparmentDetils()
    {
        return $this->belongsTo(DepartmentDetile::class,);
    }


}
