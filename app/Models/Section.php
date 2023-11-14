<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'school_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  // public function school(): BelongsTo
  // {
  //   return $this->belongsTo(School::class);
  // }
}
