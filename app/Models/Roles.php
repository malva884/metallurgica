<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

	protected $fillable = [
		'id','name'
	];

	protected $hidden = [
		'updated_at', 'created_at','guard_name'
	];
}
