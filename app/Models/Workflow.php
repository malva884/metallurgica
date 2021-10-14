<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','user_creator','commessa','status','type'
    ];
}
