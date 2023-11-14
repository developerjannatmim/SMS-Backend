<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassRoom extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'school_id',
  ];

  // public function school(): BelongsTo
  // {
  //   return $this->belongsTo(School::class, 'school_id', 'id');
  // }
}
