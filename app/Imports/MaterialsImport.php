<?php

namespace App\Imports;

use App\Models\MaterialsCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MaterialsImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MaterialsCost([
            'material'      => $row[0],
            'cost'          => $row[5],
            'date_import'   => date('Y-m-d'),
        ]);
    }
}
