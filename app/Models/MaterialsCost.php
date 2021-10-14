<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialsCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'material','cost','date_import'
    ];

    public function model(array $row)
    {
        return new MaterialsCost([
            'material'     => $row[0],
            'cost'    => $row[1],
        ]);
    }
}
