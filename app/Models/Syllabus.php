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

  // public function school(): BelongsTo
  // {
  //   return $this->belongsTo(School::class);
  // }

  protected $with = [
    'section',
    'subject',
    'class',
  ];

  public function section(): BelongsTo
  {
      return $this->belongsTo(Section::class, 'section_id', 'id');
  }

  public function class(): BelongsTo
  {
      return $this->belongsTo(Classes::class, 'class_id', 'id');
  }

  public function subject(): BelongsTo
  {
      return $this->belongsTo(Subject::class, 'subject_id', 'id');
  }
}