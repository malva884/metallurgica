<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_structure extends Model
{
    use HasFactory,UsesUuid;

    protected $fillable = [
        'id','document','document_father','document_date','document_type','revision_num'
    ];
}
