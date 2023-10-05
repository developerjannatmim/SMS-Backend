<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
 {
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'class_id',
        'section_id',
        'subject_id',
        'routine_creator',
        'room_id',
        'day',
        'starting_hour',
        'starting_minute',
        'ending_hour',
        'ending_minute',
        'school_id'
    ];
}
