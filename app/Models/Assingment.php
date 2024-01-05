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
}
