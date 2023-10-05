<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Syllabus extends Model
{
  use HasFactory;

  protected $table = 'syllabuses';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'class_id',
    'subject_id',
    'section_id',
    'file',
    'school_id'
  ];

  public function school(): BelongsTo
  {
    return $this->belongsTo(School::class);
  }

  public function classes(): HasOne
  {
    return $this->hasOne(Classes::class);
  }

  public function section(): HasOne
  {
    return $this->hasOne(Section::class);
  }

  public function subject(): HasMany
  {
    return $this->hasMany(Subject::class);
  }
}