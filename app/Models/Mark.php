<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'class_id',
        'section_id',
        'subject_id',
        'marks',
        'grade_point',
        'school_id',
        'comment'
    ];

}


