<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowUser extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','Workflow','user','data_view','aprovato','updated_at'
    ];


}
