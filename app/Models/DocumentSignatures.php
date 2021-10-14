<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSignatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','document','document_father','user','signed','signed_date','document_date','document_type'
    ];
}
