<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLaborCosts extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','machine','cost','date_import'
    ];
}
