<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory,UsesUuid;


    protected $fillable = [
        'id','document_father','title','user','user_edit','specific_number','status','created_at'
    ];
}
