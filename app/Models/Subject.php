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
    ];


    protected $casts =  [
       
        "houre" =>"integer",
        "grade" => "decimal:1",
    ];

}
