<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStatusHead extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','status','total_fianl_mp','total_mp','total_old','total_fianl','total_raw_material','total_raw_material2','total_machine_manpower_cost',
        'raw_material_machine_manpower_cost','user','total_machine_manpower_cost2'
    ];
}
