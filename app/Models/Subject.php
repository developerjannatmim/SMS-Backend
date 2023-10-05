<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}

