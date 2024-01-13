<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable =
    [
        "name",
        "description",
        "houre",
        "grade",
        "image",
    ];

    function getImageAttribute()
    {
        return env('APP_URL') . ':8000/storage/' . substr($this->attributes['image'], 7);
    }


    protected $casts =  [

        "houre" => "integer",
        "grade" => "decimal:1",
    ];
}
