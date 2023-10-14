<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'section_id',
        'school_id'
    ];

    public function getNameAttribute( $value )
    {
        return $this->attributes[ 'name' ] = ucfirst( $value );
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected $with = ['section'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
