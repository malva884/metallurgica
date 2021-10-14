<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'order', 'head', 'total_real', 'total_theoretical', 'note','cost_material','quantity_produced'
    ];
}
