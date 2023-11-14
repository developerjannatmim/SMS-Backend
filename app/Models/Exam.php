<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'exam_type',
        'starting_time',
        'ending_time',
        'total_marks',
        'status',
        'class_id',
        'section_id',
        'school_id',
    ];
    protected $with = ['section', 'class'];
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    // public function subject(): HasMany
    // {
    //     return $this->hasMany(Subjects::class);
    // }

    // public function grade(): HasMany
    // {
    //     return $this->hasMany(Grade::class);
    // }

    public function getStartingDateAttribute($date)
	{
		return $this->attributes['starting_date'] = date('Y-m-d', $date);
	}

    public function getEndingDateAttribute($date)
	{
		return $this->attributes['ending_date'] = date('Y-m-d', $date);
	}

    // public function getEndingTimeAttribute($date)
	// {
	// 	return $this->attributes['ending_time'] = date('H:i', $date);
	// }

    // public function getStartingTimeAttribute($date)
	// {
	// 	return $this->attributes['starting_time'] = date('H:i', $date);
	// }
}
