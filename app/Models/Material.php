<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "type",
        "madia_type",
        "size",
        "lecturer_id",
        "path"
    ];

    protected $casts = ["size" => "double", "lecturer_id" => "integer"];

    function getPathAttribute()
    {
        return env('APP_URL') . ':8000/storage/' . substr($this->attributes['path'], 7);
    }


    /**
     * Get the user that owns the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
