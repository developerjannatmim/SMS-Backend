<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

  protected $with = [
    'section',
    'subject',
    'class',
    'creator',
    'room',
  ];

  public function section(): BelongsTo
  {
    return $this->belongsTo(Section::class, 'section_id', 'id');
  }

  public function class (): BelongsTo
  {
    return $this->belongsTo(Classes::class, 'class_id', 'id');
  }

  public function subject(): BelongsTo
  {
    return $this->belongsTo(Subject::class, 'subject_id', 'id');
  }

  public function room(): BelongsTo
  {
    return $this->belongsTo(ClassRoom::class, 'room_id', 'id');
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'routine_creator', 'id');
  }
}
