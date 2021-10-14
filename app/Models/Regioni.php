<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regioni extends Model
{
    use HasFactory;
	protected  $table = 'regioni';

	protected $fillable = [
		'id','nome_regione'
	];



}
