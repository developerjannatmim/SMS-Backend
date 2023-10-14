<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
 {
    use HasFactory;

    protected $fillable = [
        'name',
        'class_id',
        'school_id'
    ];
    public function getNameAttribute( $value )
    {
        return $this->attributes[ 'name' ] = ucfirst( $value );
    }

    protected $with = ['class'];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }


}

