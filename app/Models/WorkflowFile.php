<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowFile extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','Workflow','user','nomeFile','path'
    ];
}
