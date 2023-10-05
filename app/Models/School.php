<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'email',
        'phone',
        'address',
        'school_info',
        'status'
    ];

    public function class(): HasMany
    {
        return $this->hasMany(Classes::class);
    }

    public function department(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function class_room(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

}
