<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'role_id',
		'class_id',
		'section_id',
		'school_id',
		'user_information'
	];
	protected $guarded = [];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function profile(): HasOne
	{
		return $this->hasOne(Profile::class);
	}

	public function role(): HasOne
	{
		return $this->hasOne(Role::class);
	}

	public function school(): BelongsTo
	{
		return $this->belongsTo(School::class);
	}

	public function parent(): HasMany
	{
		return $this->hasMany(parent::class);
	}

	public function class (): HasMany
	{
		return $this->hasMany(Classes::class);
	}


	//mutetor
	public function getNameAttribute($value)
	{
		return $this->attributes['name'] = ucfirst($value);
	}

	public function getBirthdayAttribute($date)
	{
		return $this->attributes['birthday'] = date('Y-m-d', $date);
	}

}