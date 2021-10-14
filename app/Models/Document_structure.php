<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','document','document_father','document_date','document_type'
    ];
}
